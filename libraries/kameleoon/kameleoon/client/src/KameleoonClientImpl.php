<?php
namespace Kameleoon;

use Exception;
use Kameleoon\Exception\CredentialsNotFound;
use Kameleoon\Exception\ExperimentConfigurationNotFound;
use Kameleoon\Exception\FeatureConfigurationNotFound;
use Kameleoon\Exception\NotActivated;
use Kameleoon\Exception\NotTargeted;
use Kameleoon\Exception\VariationConfigurationNotFound;
use Kameleoon\Targeting\TargetingData;
use Kameleoon\Targeting\TargetingSegment;
use Kameleoon\Targeting\TargetingTreeBuilder;

class KameleoonClientImpl implements KameleoonClient
{
    const SECONDS_BETWEEN_CONFIGURATION_UPDATE = 3600;
    const DEFAULT_TIMEOUT_MILLISECONDS = 2000;
    const API_URL = "https://api.kameleoon.com";
    const API_SSX_URL = "https://api-ssx.kameleoon.com";
    const HEXADECIMAL_ALPHABET = "0123456789ABCDEF";
    const NONCE_BYTE_LENGTH = 8;
    const DEFAULT_KAMELEOON_WORK_DIR = "/tmp/kameleoon/php-client/";

    private $siteCode;
    private $blockingClient;
    private $configurations;
    private $unsentData;
    private $configurationFilePath;
    private $commonConfiguration;
    private $visitorCode;
    private $debugMode = false;
    private $clientId;
    private $clientSecret;
    private $refreshInterval;
    private $kameleoonWorkDir;
    private $targetingData;
    private $configurationLoaded;
    private $cookieOptions;

    public function __construct($siteCode, $blocking, $configurationFilePath, $clientId, $clientSecret)
    {
        $this->siteCode = $siteCode;
        $this->blockingClient = $blocking;
        $this->configurations = (object) array(
            "experiments" => array(),
            "featureFlags" => array()
        );
        $this->unsentData = array();
        $this->targetingData = array();
        $this->configurationLoaded = false;


        $this->commonConfiguration = ConfigurationParser::parse($configurationFilePath);
        $this->kameleoonWorkDir = isset($this->commonConfiguration["kameleoon_work_dir"]) ? $this->commonConfiguration["kameleoon_work_dir"] : self::DEFAULT_KAMELEOON_WORK_DIR;
        $this->configurationFilePath = $this->kameleoonWorkDir . "kameleoon-configuration-" . $this->siteCode . ".json";
        $this->refreshInterval = isset($this->commonConfiguration["actions_configuration_refresh_interval"]) ? $this->commonConfiguration["actions_configuration_refresh_interval"] * 60 : self::SECONDS_BETWEEN_CONFIGURATION_UPDATE;
        if (isset($this->commonConfiguration["debug_mode"])) {
            $this->debugMode = $this->commonConfiguration["debug_mode"] == "true";
        }
        $this->cookieOptions = isset($this->commonConfiguration["cookie_options"]) ? $this->commonConfiguration["cookie_options"] : array();

        if (!is_dir($this->kameleoonWorkDir)) {
            mkdir($this->kameleoonWorkDir, 0755, true);
        }
        $this->clientId = isset($this->commonConfiguration["client_id"]) ? $this->commonConfiguration["client_id"] : $clientId;
        $this->clientSecret = isset($this->commonConfiguration["client_secret"]) ? $this->commonConfiguration["client_secret"] : $clientSecret;

        if (null == $this->clientId || null == $this->clientSecret) {
            throw new CredentialsNotFound("Credentials not found!");
        }
    }

    public function trackConversion($userID, $goalID, $revenue = 0.0)
    {
        $this->addData($userID, new Data\Conversion($goalID));
        $this->flush($userID);
    }

    public function triggerExperiment($userID, $experimentID, $timeOut = self::DEFAULT_TIMEOUT_MILLISECONDS)
    {
        if ($this->blockingClient) {
            return $this->performPostServerCall($this->getExperimentRegisterURL($userID, $experimentID), $timeOut);
        } else {
            $this->loadConfiguration($timeOut);
            if ($this->checkTargeting($userID, $experimentID, false))
            {
                $variationId = "reference";
                if (array_key_exists($experimentID, $this->configurations->experiments)) {
                    $xpConf = $this->configurations->experiments[$experimentID];
                    $noneVariation = true;
                    $hashDouble = $this->obtainHashDouble($experimentID, $userID, $xpConf->variationConfigurations);
                    $total = 0.0;

                    foreach ($xpConf->variationConfigurations as $vid => $variationConfiguration) {
                        $total += $variationConfiguration->deviation;
                        if ($total >= $hashDouble) {
                            $noneVariation = false;
                            $variationId = $vid;
                            break;
                        }
                    }
                    $data = "";
                    foreach ($this->getUnsentData($userID) as $d) {
                        $data .= $d->obtainFullPostTextLine() . "\n";
                    }
                    $this->writeRequestToFile($this->getExperimentRegisterURL($userID, $experimentID, $variationId, $noneVariation), $data);
                } else {
                    throw new ExperimentConfigurationNotFound('Experiment configuration not found');
                }

                if ($noneVariation)
                {
                    throw new NotActivated("Experiment not activated");
                }

                return $variationId;
            }
            else
            {
                throw new NotTargeted("Experiment not targeted");
            }
        }
    }

    public function activateFeature($userID, $featureId, $timeOut = 2000)
	{
        if ($this->blockingClient)
        {
            return $this->performPostServerCall($this->getExperimentRegisterURL($userID, $featureId), $timeOut) == "null" ? false : true;
        }
        else
        {
            $this->loadConfiguration($timeOut);
            if($this->checkTargeting($userID, $featureId, true))
            {
                $result = false;
                if (array_key_exists($featureId, $this->configurations->featureFlags))
                {
                    $xpConf = $this->configurations->featureFlags[strval($featureId)];
                    $hashDouble = $this->obtainHashDouble($featureId, $userID, $xpConf->variationConfigurations);
                    $variationId = 0;
                    $total = 0.0;
                    foreach ($xpConf->variationConfigurations as $vid => $variationConfiguration) {
                        $total += $variationConfiguration->deviation;
                        if ($total >= $hashDouble) {
                            $result = true;
                            $variationId = $vid;
                            break;
                        }
                    }
                    $data = "";
                    foreach ($this->getUnsentData($userID) as $d) {
                        $data .= $d->obtainFullPostTextLine() . "\n";
                    }
                    $this->writeRequestToFile($this->getExperimentRegisterURL($userID, $featureId, $variationId, false), $data);
                }
                else
                {
                    throw new FeatureConfigurationNotFound("Feature configuration not found");
                }
                return $result;
            }
            else
            {
                throw new NotTargeted("Feature not targeted");
            }
        }
	}

	private function loadConfiguration($timeOut)
    {
        if (!$this->configurationLoaded)
        {
            if (file_exists($this->configurationFilePath)) {
                $fp = fopen($this->configurationFilePath, "r+");
                if
                (
                    time() < filemtime($this->configurationFilePath) + $this->refreshInterval ||
                    !flock($fp, LOCK_EX)
                ) {
                    try {
                        $obj = json_decode(file_get_contents($this->configurationFilePath, true));
                        $this->configurations = Configurations::parse($obj, $this->siteCode);
                    }
                    catch (Exception $e) {
                        if (!flock($fp, LOCK_EX)) {
                            $this->updateExperimentConfiguration($timeOut);
                            flock($fp, LOCK_UN);
                        }
                    }
                } else {
                    $this->updateExperimentConfiguration($timeOut);
                    flock($fp, LOCK_UN);
                }
            } else {
                $this->updateExperimentConfiguration($timeOut);
            }
        }
        $this->configurationLoaded = true;
    }

    // TARGETING ENGINE

    // check targeting
    private function checkTargeting($userID, $containerID, $isFF)
    {
		$targeting = true;

		// performing targeting
		$xpConf = null;
		if ($isFF)
        {
            if (isset($this->configurations->featureFlags[strval($containerID)]))
            {
                $xpConf = $this->configurations->featureFlags[strval($containerID)];
            }
        }
		else
        {
            if (isset($this->configurations->experiments[strval($containerID)]))
            {
                $xpConf = $this->configurations->experiments[strval($containerID)];
            }
        }
		if ($xpConf != null)
		{
			if(true == $xpConf->forceTargeting)
			{
				// assigning targeting to container
				$targeting = true;
			}
            else
            {
                $targetingTree = null;

                if (null != $xpConf->targetingSegment)
                {
                    $targetingTree = $xpConf->targetingSegment->getTargetingTree();
                }
                // obtaining targeting checking result and assigning targeting to container
                $targeting = $this->checkTargetingTree($userID, $targetingTree);
            }
        }

        return $targeting;
    }

    // check targeting tree
    private function checkTargetingTree($userID, $targetingTree)
    {
        $result = null;

		// checking if the tree has no targeting condition
		if (null == $targetingTree)
        {
            return true;
        }
        else
        {
            // checking if the tree is a leaf
            $targetingCondition = $targetingTree->getTargetingCondition();
			if (null != $targetingCondition)
            {
                $result = $this->checkTargetingCondition($userID, $targetingCondition);
            }
            else
            {
                // computing left child result
                $leftChildResult = $this->checkTargetingTree($userID, $targetingTree->getLeftChild());

				$mustComputeRightChildResult = false;

				if (true == $leftChildResult)
                {
                    if (! $targetingTree->getOrOperator())
                    {
                        $mustComputeRightChildResult = true; // true AND anything, needs to know the anything
                    }
                }
                else if (false == $leftChildResult)
                {
                    if ($targetingTree->getOrOperator())
                    {
                        $mustComputeRightChildResult = true; // false OR anything, needs to know the anything
                    }
                }
                else if (null == $leftChildResult)
                {
                    $mustComputeRightChildResult = true; // (undefined OR anything) or (undefined AND anything), needs to know the anything
                }

				// computing right child result if we must do it
				$rightChildResult = null;
				if ($mustComputeRightChildResult)
                {
                    $rightChildResult = $this->checkTargetingTree($userID, $targetingTree->getRightChild());
                }

				// computing result
				if (true == $leftChildResult)
                {
                    if ($targetingTree->getOrOperator())
                    {
                        $result = true; // true OR anything
                    }
                    else
                    {
                        if (true == $rightChildResult)
                        {
                            $result = true; // true AND true
                        }
                        else if (false == $rightChildResult)
                        {
                            $result = false; // true AND false
                        }
                        else if (null == $rightChildResult)
                        {
                            $result = null; // true AND undefined
                        }
                    }
                }
                else if (false == $leftChildResult)
                {
                    if ($targetingTree->getOrOperator())
                    {
                        if (true == $rightChildResult)
                        {
                            $result = true; // false OR true
                        }
                        else if (false == $rightChildResult)
                        {
                            $result = false; // false OR false
                        }
                        else if (null == $rightChildResult)
                        {
                            $result = null; // false OR undefined
                        }
                    }
                    else
                    {
                        $result = false; // false AND anything
                    }
                }
                else if (null == $leftChildResult)
                {
                    if ($targetingTree->getOrOperator())
                    {
                        if (true == $rightChildResult)
                        {
                            $result = true; // undefined OR true
                        }
                        else if (false == $rightChildResult)
                        {
                            $result = null; // undefined OR false
                        }
                        else if (null == $rightChildResult)
                        {
                            $result = null; // undefined OR undefined
                        }
                    }
                    else
                    {
                        if (true == $rightChildResult)
                        {
                            $result = null; // undefined AND true
                        }
                        else if (false == $rightChildResult)
                        {
                            $result = false; // undefined AND false
                        }
                        else if (null == $rightChildResult)
                        {
                            $result = null; // undefined AND undefined
                        }
                    }
                }
			}
		}
		// returning result
		return $result;
	}

	// check targeting condition
	private function checkTargetingCondition($userID, $targetingCondition)
    {
        $result = null;

		// obtaining targeting
		if ($targetingCondition != null)
        {
            $result = $targetingCondition->check($this->getTargetingData($userID));

            // correcting targeting result in the case an exclusion rule is asked for
            if (true != $targetingCondition->getInclude())
            {
                if (null == $result)
                {
                    $result = true;
                }
                else
                {
                    $result = !$result;
                }
            }
        }
        else
        {
            $result = true;
        }

		// returning result
		return $result;
	}

    private function obtainHashDouble($containerID, $userID, $variationConfigurations)
    {
        $respoolTimes = array();
        foreach ($variationConfigurations as $v) {
            if ($v->respoolTime != null) {
                array_push($respoolTimes, $v->respoolTime);
            }
        }
        return floatval(intval(substr(hash("sha256", $userID . $containerID . join("", $respoolTimes)), 0, 8), 16) / pow(2, 32));
    }

    private function getCommonSSXParameters($userID)
    {
        return "nonce=" . self::obtainNonce() . "&siteCode=" . $this->siteCode . "&visitorCode=" . $userID;
    }

    private function getDataTrackingURL($userID)
    {
        return self::API_SSX_URL . "/dataTracking?" . $this->getCommonSSXParameters($userID);
    }

    private function getExperimentRegisterURL($userID, $experimentID, $variationId = null, $noneVariation = null)
    {
        $url = self::API_SSX_URL . "/experimentTracking?" . $this->getCommonSSXParameters($userID) . "&experimentId=" . $experimentID . "";
        if (!is_null($variationId)) {
            $url .= "&variationId=" . $variationId;
        }
        if ($this->debugMode) {
            $debugParameters = "";
            try {
                $debugParameters .= "&debug=true";

                // Add current url
                $currentUrl = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
                $debugParameters .= "&url=" . rawurlencode($currentUrl);

                // Add visitor IP
                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                $debugParameters .= "&ip=" . rawurlencode($ip);

                $debugParameters .= "&ua=" . rawurlencode($_SERVER['HTTP_USER_AGENT']);
            } catch (Exception $e) {}
            $url .= $debugParameters;
        }
        return $url . ($noneVariation ? "&noneVariation=true" : "");
    }

    public static function obtainNonce()
    {
        $alphabetLength = strlen(self::HEXADECIMAL_ALPHABET);
        $result = "";

        for ($i = 0; $i < self::NONCE_BYTE_LENGTH * 2; $i++) {
            $randomNumber = floor((mt_rand() / mt_getrandmax()) * $alphabetLength);
            $result .= substr(self::HEXADECIMAL_ALPHABET, $randomNumber, 1);
        }

        return $result;
    }

    private function updateExperimentConfiguration($timeOut)
    {
        try
        {
            $tokenRequest = curl_init(self::API_URL . "/oauth/token");
            curl_setopt($tokenRequest, CURLOPT_POST, 1);
            curl_setopt($tokenRequest,CURLOPT_TIMEOUT, $timeOut);
            curl_setopt($tokenRequest, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($tokenRequest, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret);
            $tokenOutput = curl_exec($tokenRequest);
            curl_close($tokenRequest);
            $token = json_decode($tokenOutput)->access_token;

            $experimentsQuery = "%7Bexperiments%28perPage%3A-1%2C%20filter%3A%5B%7Bfield%3A%22status%22%2Coperator%3AIN%2Cparameters%3A%22%5B%5C%22ACTIVE%5C%22%2C%5C%22DEVIATED%5C%22%2C%5C%22USED_AS_PERSONALIZATION%5C%22%5D%22%7D%2C%7Bfield%3A%22type%22%2Coperator%3AIN%2Cparameters%3A%22%5B%5C%22SERVER_SIDE%5C%22%2C%5C%22HYBRID%5C%22%5D%22%7D%5D%29%7Bid%2Cname%2Ctype%2CsiteId%2CsiteCode%2Cstatus%2Cvariations%7Bid%2CcustomJson%7D%2Cdeviations%7BvariationId%2Cvalue%7D%2CrespoolTime%7BvariationId%2Cvalue%7D%2Csegment%7Bid%2Cname%2CconditionsData%7BfirstLevelOrOperators%2CfirstLevel%7BorOperators%2Cconditions%7BtargetingType%2CisInclude%20...%20on%20CustomDataTargetingCondition%7BcustomDataIndex%2Cvalue%2CvalueMatchType%7D%7D%7D%7D%7D%7D%7D";
            $experimentsRequest = curl_init(self::API_URL . "/graphql?query=" . $experimentsQuery);
            curl_setopt($experimentsRequest,CURLOPT_TIMEOUT, $timeOut);
            curl_setopt($experimentsRequest, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($experimentsRequest, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($experimentsRequest, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ));
            $experimentsOutput = curl_exec($experimentsRequest);
            curl_close($experimentsRequest);

            $ffQuery = "%7BfeatureFlags%28perPage%3A-1%2C%20filter%3A%5B%7Bfield%3A%22status%22%2Coperator%3AIN%2Cparameters%3A%22%5B%5C%22ACTIVE%5C%22%2C%5C%22DEVIATED%5C%22%2C%5C%22USED_AS_PERSONALIZATION%5C%22%5D%22%7D%2C%5D%29%7Bid%2Cname%2CidentificationKey%2CfeatureFlagSdkLanguageType%2CsiteId%2CsiteCode%2Cstatus%2Cvariations%7Bid%2CcustomJson%7D%2Cdeviations%7BvariationId%2Cvalue%7D%2CrespoolTime%7BvariationId%2Cvalue%7D%2Csegment%7Bid%2Cname%2CconditionsData%7BfirstLevelOrOperators%2CfirstLevel%7BorOperators%2Cconditions%7BtargetingType%2CisInclude%20...%20on%20CustomDataTargetingCondition%7BcustomDataIndex%2Cvalue%2CvalueMatchType%7D%7D%7D%7D%7D%7D%7D";
            $ffRequest = curl_init(self::API_URL . "/graphql?query=" . $ffQuery);
            curl_setopt($ffRequest,CURLOPT_TIMEOUT, $timeOut);
            curl_setopt($ffRequest, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ffRequest, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ffRequest, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ));
            $ffOutput = curl_exec($ffRequest);
            curl_close($ffRequest);

            $json = json_decode($experimentsOutput)->data;
            $json->featureFlags = json_decode($ffOutput)->data->featureFlags;

            if (isset($json->experiments) && isset($json->featureFlags))
            {
                file_put_contents($this->configurationFilePath, json_encode($json));
                $this->configurations = Configurations::parse($json, $this->siteCode);
            }
            else
            {
                $json = json_decode(file_get_contents($this->configurationFilePath, true));
                file_put_contents($this->configurationFilePath, json_encode($json));
            }

            $this->configurations = Configurations::parse($json, $this->siteCode);
        } catch (Exception $e) {}
    }

    private function getUnsentUsers()
    {
        return array_keys($this->unsentData);
    }

    private function getUnsentData($userID)
    {
        if (!array_key_exists($userID, $this->unsentData)) {
            $this->unsentData[$userID] = array();
        }
        return $this->unsentData[$userID];
    }

    private function getTargetingData($userID)
    {
        if (!isset($this->targetingData[$userID]))
        {
            $this->targetingData[$userID] = array();
        }
        return $this->targetingData[$userID];
    }

    private function emptyUnsentData($userID)
    {
        unset($this->unsentData[$userID]);
    }

    public function addData($userID, ...$data)
    {
        $this->getUnsentData($userID);
        if (!isset($this->targetingData[$userID]))
        {
            $this->targetingData[$userID] = array();
        }
        foreach ($data as $datum) {
            array_push($this->unsentData[$userID], $datum);
            array_push($this->targetingData[$userID], new TargetingData($datum));
        }
    }

    public function flush($userID = null)
    {
        if (!is_null($userID)) {
            $data = "";
            foreach ($this->getUnsentData($userID) as $d) {
                $data .= $d->obtainFullPostTextLine() . PHP_EOL;
            }
            $this->writeRequestToFile($this->getDataTrackingURL($userID), $data);
            $this->emptyUnsentData($userID);
        } else {
            foreach ($this->getUnsentUsers() as $user) {
                $this->flush($user);
            }
        }
    }

    public function performPostServerCall($url, $timeOut, $data = null)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, $timeOut);
        if (!is_null($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($curl);
        if (curl_error($curl)) {
            throw new Exception('API-SSX call returned status code 404');
        }
        curl_close($curl);
        return $response;
    }

    private function getRequestsFileName()
    {
        return "requests-" . floor(time() / 60) . ".sh";
    }

    private function writeRequestToFile($url, $data)
    {
        $requestText = "curl -s -S --tlsv1.2 --tls-max 1.2 -X POST -H \"Kameleoon-Client: sdk/php/2.0.5\" \"" . $url . "\"";
        if ($data != null) {
            $requestText .= " -d '" . $data . "'";
        }
        $requestText .= " & r=\${r:=0};((r=r+1));if [ \$r -eq 1000 ];then r=0;wait;fi;" . PHP_EOL;
        file_put_contents($this->kameleoonWorkDir . $this->getRequestsFileName(), $requestText, FILE_APPEND | LOCK_EX);
    }

    // Here you must provide your own base domain, eg mydomain.com
    public function obtainVisitorCode($topLevelDomain = null, $visitorCode = null)
    {
        if (isset($this->visitorCode)) {
            return $this->visitorCode;
        } else {
            if (isset($_COOKIE["kameleoonVisitorCode"])) {
                $value = $_COOKIE["kameleoonVisitorCode"];
                if (strpos($value, "_js_") !== false) {
                    $visitorCode = substr($value, 4);
                } else {
                    $visitorCode = $value;
                }
            }
            if (is_null($visitorCode)) {
                $alphabet = "abcdefghijklmnopqrstuvwxyz0123456789";
                $alphabetLength = strlen($alphabet);
                $visitorCode = "";
                for ($i = 0; $i < 16; $i++) {
                    $randomNumber = floor((mt_rand() / mt_getrandmax()) * $alphabetLength);
                    $visitorCode .= substr($alphabet, $randomNumber, 1);
                }
            }

            $secure = false;
            $httpOnly = false;
            $samesite = "None";
            $domain = null;

            if (isset($this->cookieOptions["secure"])) {
                $secure = $this->cookieOptions["secure"];
            }
            if (isset($this->cookieOptions["http_only"])) {
                $httpOnly = $this->cookieOptions["http_only"];
            }
            if (isset($this->cookieOptions["samesite"])) {
                $samesite = $this->cookieOptions["samesite"];
            }
            if (!is_null($topLevelDomain)) {
                $domain = $topLevelDomain;
            }
            elseif (isset($this->cookieOptions["domain"])) {
                $domain = $this->cookieOptions["domain"];
            }
            else {
                throw new InvalidArgumentException('Domain is required');
            }

            if (version_compare(phpversion(), '7.3', '<')) {
                setcookie("kameleoonVisitorCode", $visitorCode, time() + 32832000, "/", $domain, $secure, $httpOnly);
            } else {
                $cookie_options = array (
                    "expires" => time() + 32832000,
                    "path" => '/',
                    "domain" => $domain,
                    "secure" => $secure,
                    "httponly" => $httpOnly,
                    "samesite" => $samesite
                );
                setcookie("kameleoonVisitorCode", $visitorCode, $cookie_options);
            }
            $this->visitorCode = (string) $visitorCode;
            return $visitorCode;
        }
    }

    public function obtainVariationAssociatedData($variationId)
    {
        $result = null;
        foreach ($this->configurations->experiments as $experiment) {
            foreach ($experiment->variationConfigurations as $vid => $variationConfiguration) {
                if ($vid == $variationId || strval($vid) == $variationId) {
                    $result = $variationConfiguration->customJson;
                }
            }
        }
        if ($result == null)
        {
            throw new VariationConfigurationNotFound("Variation configuration not found");
        }
        return $result;
    }

    public function obtainFeatureVariable($featureIdOrName, $variableName)
    {
        $result = null;
        foreach ($this->configurations->featureFlags as $id => $ff) {
            foreach ($ff->variationConfigurations as $vid => $variationConfiguration) {
                if ($id == $featureIdOrName || strval($id) == $featureIdOrName || $featureIdOrName == $ff->identificationKey) {
                    try {
                        if ($vid != 0)
                        {
                            $result = $variationConfiguration->customJson->{$variableName}->value;
                        }
                    }
                    catch (Exception $e){}
                }
            }
        }
        if ($result == null)
        {
            throw new FeatureConfigurationNotFound("Feature configuration not found");
        }
        return $result;
    }
}

class VariationConfiguration
{
    public $deviation;
    public $respoolTime;
    public $customJson;

    public function __construct($deviation, $respoolTime, $customJson)
    {
        $this->deviation = $deviation;
        $this->respoolTime = $respoolTime;
        try {
            $this->customJson = json_decode($customJson);
        } catch (Exception $e) {
            $this->customJson = null;
        }
    }
}

class Experiment
{
    public $variationConfigurations;
    public $forceTargeting;
    public $targetingSegment;

    public function __construct($experiment)
    {
        $this->variationConfigurations = array();
        $this->forceTargeting = isset($ff->forceTargeting) ? $ff->forceTargeting : false;
        $this->targetingSegment = null;

        if ($experiment->segment != null)
        {
            $this->targetingSegment = new TargetingSegment();
            $targetingTreeBuilder = new TargetingTreeBuilder();
            $targetingTree = $targetingTreeBuilder->createTargetingTree($experiment->segment->conditionsData);
            $this->targetingSegment->setTargetingTree($targetingTree);
        }

        foreach ($experiment->deviations as $deviation) {
            $variationId = $deviation->variationId == "origin" ? 0 : intval($deviation->variationId);
            $deviation = floatval($deviation->value);
            $respoolTime = null;
            if (isset($experiment->respoolTime)) {
                foreach ($experiment->respoolTime as $rt) {
                    if ($rt->variationId == $variationId) {
                        $respoolTime = floatval($rt->value);
                    }
                }
            }
            $customJson = null;
            foreach ($experiment->variations as $variation) {
                if ($variation->id == $variationId) {
                    $customJson = $variation->customJson;
                }
            }
            $this->variationConfigurations[$variationId] = new VariationConfiguration($deviation, $respoolTime, $customJson);
        }
    }
}

class FeatureFlag
{
    public $variationConfigurations;
    public $forceTargeting;
    public $targetingSegment;
    public $identificationKey;

    public function __construct($ff)
    {
        $this->variationConfigurations = array();
        $this->identificationKey = isset($ff->identificationKey) ? $ff->identificationKey : null;
        $this->forceTargeting = isset($ff->forceTargeting) ? $ff->forceTargeting : false;

        $this->targetingSegment = null;

        if ($ff->segment != null)
        {
            $this->targetingSegment = new TargetingSegment();
            $targetingTreeBuilder = new TargetingTreeBuilder();
            $targetingTree = $targetingTreeBuilder->createTargetingTree($ff->segment->conditionsData);
            $this->targetingSegment->setTargetingTree($targetingTree);
        }

        foreach ($ff->deviations as $deviation) {
            $variationId = $deviation->variationId == "origin" ? 0 : intval($deviation->variationId);
            $deviation = floatval($deviation->value);
            $respoolTime = null;
            if (isset($ff->respoolTime)) {
                foreach ($ff->respoolTime as $rt) {
                    if ($rt->variationId == $variationId) {
                        $respoolTime = floatval($rt->value);
                    }
                }
            }
            $customJson = null;
            foreach ($ff->variations as $variation) {
                if ($variation->id == $variationId) {
                    $customJson = $variation->customJson;
                }
            }
            $this->variationConfigurations[$variationId] = new VariationConfiguration($deviation, $respoolTime, $customJson);
        }

        // Temporary fix for ff
        foreach ($ff->variations as $variation) {
            if (!isset($this->variationConfigurations[$variation->id]))
            {
                $deviation = 1 - $this->variationConfigurations["0"]->deviation;
                $respoolTime = null;
                if (isset($ff->respoolTime)) {
                    if (isset($ff->respoolTime[$variation->id])) {
                        $respoolTime = floatval($ff->respoolTime[$variation->id]);
                    }
                }
                $customJson = $variation->customJson;
                $this->variationConfigurations[$variation->id] = new VariationConfiguration($deviation, $respoolTime, $customJson);
            }
        }
    }
}

class Configurations
{
    public static function parse($json, $siteCode)
    {
        $configurations = (object) array();
        $configurations->experiments = array();
        $configurations->featureFlags = array();

        try {
            foreach ($json->experiments as $experiment) {
                if ($experiment->siteCode == $siteCode) {
                    $configurations->experiments[$experiment->id] = new Experiment($experiment);
                }
            }
        } catch (Exception $e) {
        }
        try {
            foreach ($json->featureFlags as $ff) {
                if ($ff->siteCode == $siteCode) {
                    $configurations->featureFlags[$ff->id] = new FeatureFlag($ff);
                }
            }
        } catch (Exception $e) {
        }
        return $configurations;
    }
}

class ConfigurationParser
{
    public static function parse($configurationFilePath)
    {
        $configuration = array();
        try {
            if (file_exists($configurationFilePath) && is_file($configurationFilePath)) {
                $config = file_get_contents($configurationFilePath, true);
                $configuration = json_decode($config, true);
            }
        } catch (Exception $e) {}
        return $configuration;
    }
}
