<?php
namespace Kameleoon;

class KameleoonClientImpl implements KameleoonClient
{
    const SECONDS_BETWEEN_CONFIGURATION_UPDATE = 3600;
    const DEFAULT_TIMEOUT_MILLISECONDS = 2000;
    const API_SSX_URL = "https://api-ssx.kameleoon.com";
    const HEXADECIMAL_ALPHABET = "0123456789ABCDEF";
    const NONCE_BYTE_LENGTH = 8;
    const DEFAULT_KAMELEOON_WORK_DIR = "/tmp/kameleoon/php-client/";

    private $siteCode;
    private $blockingClient;
    private $experimentConfigurations;
    private $unsentData;
    private $configurationFilePath;
    private $commonConfiguration;

    public function __construct($siteCode, $blocking, $configurationFilePath)
    {
        $this->siteCode = $siteCode;
        $this->blockingClient = $blocking;
        $this->experimentConfigurations = array();
        $this->unsentData = array();

        $this->commonConfiguration = ConfigurationParser::parse($configurationFilePath);
        $this->kameleoonWorkDir = isset($this->commonConfiguration["kameleoon_work_dir"]) ? $this->commonConfiguration["kameleoon_work_dir"] : self::DEFAULT_KAMELEOON_WORK_DIR;
        $this->configurationFilePath = $this->kameleoonWorkDir . "kameleoonConfiguration.json";
        $this->refreshInterval = isset($this->commonConfiguration["actions_configuration_refresh_interval"]) ? $this->commonConfiguration["actions_configuration_refresh_interval"] : self::SECONDS_BETWEEN_CONFIGURATION_UPDATE;

        if (!is_dir($this->kameleoonWorkDir)) {
            mkdir($this->kameleoonWorkDir, 0755, true);
        }
    }

    public function trackConversion($userID, $goalID, $revenue = 0.0)
    {
        $this->addData($userID, new Data\Conversion($goalID));
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
                    time() < filemtime($this->configurationFilePath) + $this->refreshInterval ||
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
    
                foreach ($xpConf->variationConfigurations as $vid => $variationConfiguration)
                {
                    $total += $variationConfiguration->deviation;
                    if ($total >= $hashDouble)
                    {
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
            }
            else
            {
                throw new Exceptions\ExperimentConfigurationNotFound('Experiment configuration not found');
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
        return "siteCode=" . $this->siteCode . "&visitorCode=" . $userID;
    }

    private function getDataTrackingURL ($userID)
    {
        return self::API_SSX_URL . "/dataTracking?" . $this->getCommonSSXParameters($userID);
    }

    private function getExperimentRegisterURL ($userID, $experimentID, $variationId = NULL, $noneVariation = NULL)
    {
        $url = self::API_SSX_URL . "/experimentTracking?nonce=" . self::obtainNonce() . $this->getCommonSSXParameters($userID) . "&experimentId=" . $experimentID . "";
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
            throw new \Exception('API-SSX call returned status code 404');
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
                $data .= $d->obtainFullPostTextLine() . PHP_EOL;
            }
            $this->writeRequestToFile($this->getDataTrackingURL($userID), $data);
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
            throw new \Exception('API-SSX call returned status code 404');
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
        $requestText = "curl -X POST \"" . $url . "\"";
        if ($data != NULL) {
            $requestText .= " -d '" . $data . "'";
        }
        $requestText .= " & r=\${r:=0};((r=r+1));if [ \$r -eq 1000 ];then r=0;wait;fi;" . PHP_EOL;
        file_put_contents($this->kameleoonWorkDir . $this->getRequestsFileName(), $requestText, FILE_APPEND | LOCK_EX);
    }

    // Here you must provide your own base domain, eg mydomain.com
    public function obtainVisitorCode($topLevelDomain, $visitorCode = NULL)
    {
        if (isset($_COOKIE["kameleoonVisitorCode"]))
        {
            $value = $_COOKIE["kameleoonVisitorCode"];
            if (strpos($value, "_js_") !== false)
            {
                $visitorCode = substr($value, 4);
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

        @setcookie("kameleoonVisitorCode", $visitorCode, time() + 32832000, "/", $topLevelDomain);
        
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
        } catch (\Exception $e) {}
        return $experimentsConfigurations;
    }
}

class ConfigurationParser
{
    public static function parse($configurationFilePath)
    {
        $configuration = array();
        try {
            if (file_exists($configurationFilePath) && is_file($configurationFilePath))
            {
                $config = file_get_contents($configurationFilePath, true);
                foreach (preg_split("/\n/", $config) as $line)
                {
                    $parameters = explode("=", $line);
                    $key = trim($parameters[0]);
                    $value = trim($parameters[1]);
                    $configuration[$key] = $value;
                }
            }
        } catch (\Exception $e) {}
        return $configuration;
    }
}

?>
