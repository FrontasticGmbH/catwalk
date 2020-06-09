<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Kore\DataObject\DataObject;

use Frontastic\Common\ReplicatorBundle\Domain\Customer;
use Frontastic\Common\ReplicatorBundle\Domain\Project;

use Frontastic\Common\AccountApiBundle\Domain\Session;

/**
 * @type
 */
class Context extends DataObject
{
    /**
     * Symfony environment
     *
     * @var string
     *
     * @deprecated Use the {@link applicationEnvironment()} instead
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
     * @var array
     */
    public $projectConfiguration = [];

    /**
     * @var array
     */
    public $projectConfigurationSchema = [];

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

    /**
     * @var string
     */
    public $host;

    public function __construct(array $values = [])
    {
        parent::__construct($values);

        if (!$this->session) {
            $this->session = new Session();
        }
    }

    public function applicationEnvironment(): string
    {
        return Environment::mapFromFramework($this->environment);
    }

    public function hasFeature(string $featureFlag): bool
    {
        if (!isset($this->featureFlags[$featureFlag])) {
            return false;
        }

        return (bool) $this->featureFlags[$featureFlag];
    }

    public function isProduction(): bool
    {
        return $this->environment === 'prod' || $this->environment === 'production';
    }
}
