<?php

// @codingStandardsIgnoreFile

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("KameleoonClientImpl.php");

class KameleoonClientFactory
{
    private $clients = [];

    public static function create($siteCode, $blocking = false, $configurationPath = "/tmp/kameleoon-client-configuration.json")
    {
        if (!in_array($siteCode, self::getInstance()->clients)) {
            self::getInstance()->clients[$siteCode] = new KameleoonClientImpl($siteCode, $blocking, $configurationPath);
        }
        return self::getInstance()->clients[$siteCode];
    }
    
    public static function forget($siteCode)
    {
        unset(self::getInstance()->clients[$siteCode]);
    }

    private static $_instance = null;

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new KameleoonClientFactory();
        }

        return self::$_instance;
    }
}

