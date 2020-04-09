<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

class Environment
{
    const DEVELOPMENT = 'development';
    const TESTING = 'testing';
    const STAGING = 'staging';
    const PRODUCTION = 'production';

    private static $symfonyEnvironmentMap = [
        'dev' => self::DEVELOPMENT,
        'test' => self::TESTING,
        'staging' => self::STAGING,
        'prod' => self::PRODUCTION,
    ];

    public static function mapFromFramework(string $frameworkEnvironment): string
    {
        if (!isset(self::$symfonyEnvironmentMap[$frameworkEnvironment])) {
            throw new \OutOfBoundsException('Unknown environment ' . $frameworkEnvironment);
        }

        return self::$symfonyEnvironmentMap[$frameworkEnvironment];
    }
}
