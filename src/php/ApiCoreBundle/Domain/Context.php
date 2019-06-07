<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Kore\DataObject\DataObject;

use Frontastic\Common\ReplicatorBundle\Domain\Customer;
use Frontastic\Common\ReplicatorBundle\Domain\Project;

use Frontastic\Common\AccountApiBundle\Domain\Session;

class Context extends DataObject
{
    /**
     * @var string
     *
     * @todo This seems to be the Symfony env, we should better use the application environment directly.
     */
    public $environment = 'prod';

    /**
     * @var Customer
     */
    public $customer;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var string
     */
    public $currency = 'EUR';

    /**
     * @var string[]
     */
    public $routes = [];

    /**
     * @var Session
     */
    public $session = null;

    /**
     * @var [string => bool]
     */
    public $featureFlags = [];

    public function __construct(array $values = [])
    {
        parent::__construct($values);

        if (!$this->session) {
            $this->session = new Session();
        }
    }

    public function applicationEnvironment(): string
    {
        return $this->mapFrameworkToApplicationEnvironment($this->environment);
    }

    public function hasFeature(string $featureFlag): bool
    {
        if (!isset($this->featureFlags[$featureFlag])) {
            return false;
        }

        return (bool) $this->featureFlags[$featureFlag];
    }

    private function mapFrameworkToApplicationEnvironment(string $frameworkEnvironment): string
    {
        $map  =  [
            'dev' => 'development',
            'test' => 'testing',
            'staging' => 'staging',
            'prod' => 'production',
        ];

        if (!isset($map[$frameworkEnvironment])) {
            throw new \OutOfBoundsException('Unknown environment ' . $frameworkEnvironment);
        }

        return $map[$frameworkEnvironment];
    }

    public function isProduction(): bool
    {
        return $this->environment === 'prod' || $this->environment === 'production';
    }
}
