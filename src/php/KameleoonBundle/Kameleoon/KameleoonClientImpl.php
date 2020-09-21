<?php

require_once("KameleoonClient.php");
require_once("KameleoonException.php");
require_once("Data.php");

class KameleoonClientImpl implements KameleoonClient
{
    const SECONDS_BETWEEN_CONFIGURATION_UPDATE = 3600;
    const DEFAULT_TIMEOUT_MILLISECONDS = 2000;
    const API_SSX_URL = "https://api-ssx.kameleoon.com";
    const HEXADECIMAL_ALPHABET = "0123456789ABCDEF";
    const NONCE_BYTE_LENGTH = 8;

    private $siteCode;
    private $blockingClient;
    private $experimentConfigurations;
    private $unsentData;
    private $configurationFilePath;

    public function __construct($siteCode, $blocking, $configurationPath)
    {
        $this->siteCode = $siteCode;
        $this->blockingClient = $blocking;
        $this->experimentConfigurations = array();
        $this->unsentData = array();
        $this->configurationFilePath = $configurationPath;
    }

    public function trackConversion($userID, $goalID, $revenue = 0.0)
    {
        $this->addData($userID, new Conversion($goalID));
        $this->flush($userID);
    }

    public function triggerExperiment($userID, $experimentID, $timeOut = self::DEFAULT_TIMEOUT_MILLISECONDS)
    {
        if ($this->blockingClient)
        {
            return $this->performPostServerCall($this->getExperimentRegisterURL($userID, $experimentID), $timeOut);
        }
        else
        {
            if (file_exists($this->configurationFilePath))
            {
                $fp = fopen($this->configurationFilePath, "r+");
                if
                (
                    time() < filemtime($this->configurationFilePath) + self::SECONDS_BETWEEN_CONFIGURATION_UPDATE ||
                    !flock($fp, LOCK_EX)
                )
                {
                    $this->experimentConfigurations = array();
                    $obj = json_decode(file_get_contents($this->configurationFilePath, true));
                    foreach($obj as $key => $value) {
                        $this->experimentConfigurations[$key] = $value;
                    }
                }
                else
                {
                    $this->updateExperimentConfiguration($timeOut);
                    flock($fp, LOCK_UN);
                }
            }
            else
            {
                $this->updateExperimentConfiguration($timeOut);
            }

            $variationId = "reference";
            if (array_key_exists($experimentID, $this->experimentConfigurations))
            {
                $xpConf = $this->experimentConfigurations[$experimentID];
                $noneVariation = true;
                $hashDouble = $this->obtainHashDouble($experimentID, $userID, $xpConf->variationConfigurations);
                $total = 0.0;
    
                foreach ($xpConf->variationConfigurations as $variationId => $variationConfiguration)
                {
                    $total += $variationConfiguration->deviation;
                    if ($total >= $hashDouble)
                    {
                        $noneVariation = false;
                        $variationId = $variationId;
                        break;
                    }
                }
                $data = "";
                foreach ($this->getUnsentData($userID) as $d) {
                    $data .= $d->obtainFullPostTextLine() . "\n";
                }
                $this->performPostServerCall($this->getExperimentRegisterURL($userID, $experimentID, $variationId, $noneVariation), 200, $data);
            }
            else
            {
                throw new ExperimentConfigurationNotFound('Experiment configuration not found');
            }
            return $variationId;
        }
    }

    private function obtainHashDouble ($containerID, $userID, $variationConfigurations)
    {
        $respoolTimes = array();
        foreach ($variationConfigurations as $v)
        {
            if ($v->respoolTime != null)
            {
                array_push($respoolTimes, $v->respoolTime);
            }
        }
        return floatval(intval(substr(hash("sha256", $containerID . $userID . join("", $respoolTimes)), 0, 8), 16) / pow(2, 32));
    }

    private function getCommonSSXParameters ($userID)
    {
        return "&nonce=" . self::obtainNonce() . "&siteCode=" . $this->siteCode . "&visitorCode=" . $userID;
    }

    private function getDataTrackingURL ($userID)
    {
        return self::API_SSX_URL . "/dataTracking?" . $this->getCommonSSXParameters($userID);
    }

    private function getExperimentRegisterURL ($userID, $experimentID, $variationId = NULL, $noneVariation = NULL)
    {
        $url = self::API_SSX_URL . "/experimentTracking?" . $this->getCommonSSXParameters($userID) . "&experimentId=" . $experimentID . "";
        if (!is_null($variationId))
        {
            $url .= "&variationId=" . $variationId;
        }
        return $url . ($noneVariation ? "&noneVariation=true" : "");
    }

    public static function obtainNonce ()
    {
        $alphabetLength = strlen(self::HEXADECIMAL_ALPHABET);
        $result = "";

        for ($i = 0; $i < self::NONCE_BYTE_LENGTH * 2; $i++)
        {
            $randomNumber = floor((mt_rand() / mt_getrandmax()) * $alphabetLength);
            $result .= substr(self::HEXADECIMAL_ALPHABET, $randomNumber, 1);
        }

        return $result;
    }

    private function updateExperimentConfiguration ($timeOut)
    {
        $curl = curl_init(self::API_SSX_URL . "/experimentsConfigurations?siteCode=" . $this->siteCode);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, $timeOut);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new Exception('API-SSX call returned status code 404');
        }
        curl_close($curl);
        $this->experimentConfigurations = ExperimentsConfigurations::parse($response);
        file_put_contents($this->configurationFilePath, json_encode($this->experimentConfigurations));
    }

    private function getUnsentUsers ()
    {
        return array_keys($this->unsentData);
    }

    private function getUnsentData ($userID)
    {
        if (!array_key_exists($userID, $this->unsentData))
        {
            $this->unsentData[$userID] = array();
        }
        return $this->unsentData[$userID];
    }

    private function emptyUnsentData ($userID)
    {
        unset($this->unsentData[$userID]);
    }

    public function addData($userID, ...$data)
    {
        $this->getUnsentData($userID);
        foreach ($data as $datum)
        {
            array_push($this->unsentData[$userID], $datum);
        }
    }

    public function flush($userID = NULL)
    {
        if (! is_null($userID))
        {
            $data = "";
            foreach ($this->getUnsentData($userID) as $d) {
                $data .= $d->obtainFullPostTextLine() . "\n";
            }
            $this->performPostServerCall($this->getDataTrackingURL($userID), 200, $data);
            $this->emptyUnsentData($userID);
        }
        else
        {
            foreach ($this->getUnsentUsers() as $user)
            {
                $this->flush($user);
            }
        }
    }

    public function performPostServerCall($url, $timeOut, $data = NULL)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, $timeOut);
        if (! is_null($data))
        {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($curl);
        if (curl_error($curl)) {
            throw new Exception('API-SSX call returned status code 404');
        }
        curl_close($curl);
        return $response;
    }

    // Here you must provide your own base domain, eg mydomain.com
    public function obtainVisitorCode($topLevelDomain, $visitorCode = NULL)
    {
        if (isset($_COOKIE["kameleoonVisitorCode"]))
        {
            $value = $_COOKIE["kameleoonVisitorCode"];
            if (strpos($value, "_js_") !== false)
            {
                $visitorCode = substr($value, 0, 4);
            }
            else
            {
                $visitorCode = $value;
            }
        }
        if (is_null($visitorCode))
        {
            $alphabet = "abcdefghijklmnopqrstuvwxyz0123456789";
            $alphabetLength = strlen($alphabet);
            $visitorCode = "";
            for ($i = 0; $i < 16; $i++)
            {
                $randomNumber = floor((mt_rand() / mt_getrandmax()) * $alphabetLength);
                $visitorCode .= substr($alphabet, $randomNumber, 1);
            }
        }

        setcookie("kameleoonVisitorCode", $visitorCode, time() + 32832000, "/", $topLevelDomain);
        
        return $visitorCode;
    }
}

class VariationConfiguration
{
    public $deviation;
    public $respoolTime;

    public function __construct ($deviation, $respoolTime)
    {
        $this->deviation = $deviation;
        $this->respoolTime = $respoolTime;
    }
}

class ExperimentConfiguration
{
    public $variationConfigurations;

    public function __construct($variationsParameters)
    {
        $this->variationConfigurations = array();
        for ($i = 1; $i < count($variationsParameters); $i += 3)
        {
            $variationId = $variationsParameters[$i] == "" ? "reference" : $variationsParameters[$i];
            $deviation = floatval($variationsParameters[$i + 1] == "" ? null : $variationsParameters[$i + 1]);
            $respoolTime = floatval($variationsParameters[$i + 2] == "" ? null : $variationsParameters[$i + 2]);
            $this->variationConfigurations[$variationId] = new VariationConfiguration($deviation, $respoolTime);
        }
    }
}

class ExperimentsConfigurations
{
    public static function parse($response)
    {
        $experimentsConfigurations = array();
        try {
            foreach (preg_split("/\n/", $response) as $line)
            {
                $parameters = explode(",", $line);
                $experimentID = $parameters[0];
                $experimentsConfigurations[$experimentID] = new ExperimentConfiguration($parameters);
            }
        } catch (Exception $e) {}
        return $experimentsConfigurations;
    }
}

?>