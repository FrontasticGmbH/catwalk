<?php
namespace Kameleoon;

class KameleoonClientFactory
{
    private $clients = [];

    public static function create($siteCode, $blocking = false, $configurationFilePath = "/etc/kameleoon/php-client.conf")
    {
        if (!in_array($siteCode, self::getInstance()->clients)) {
            self::getInstance()->clients[$siteCode] = new KameleoonClientImpl($siteCode, $blocking, $configurationFilePath);
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

?>