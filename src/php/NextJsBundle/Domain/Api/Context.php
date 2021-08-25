<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @replaces Frontastic\Catwalk\ApiCoreBundle\Domain\Context
 * @type
 */
class Context extends DataObject
{
    /**
     * Result of {@link Frontastic\Catwalk\ApiCoreBundle\Domain\Context.applicationEnvironment()}
     * @var string
     */
    public string $environment = 'production';

    /**
     * @required
     * @removed data is only relevant in Frontastic studio
     */
    // public OriginalCustomer $customer;

    /**
     * @required
     * @var Project
     */
    public Project $project;

    /**
     * @required
     * @var array
     * @todo complete data in PHP code so that $projectConfigurationSchema is not needed to be transmitted.
     */
    public array $projectConfiguration = [];

    /**
     * @removed Only needed for completion of defaults which we should handle in PHP
     */
    // public $projectConfigurationSchema = [];

    /**
     * @required
     * @todo Needs to be the fully fledged locale encoded as a properly built locale string
     *       `language[_territory[.codeset]][@modifier]`
     * @var string
     */
    public string $locale;

    /**
     * @removed $sessionData is now an arbitrary hash-map and available as dedicatedly requestable
     */
    // public $session = null;

    /**
     * @var array<string, bool>
     */
    public array $featureFlags = [];

    /**
     * @removed Host can now be obtained by retrieving the Request
     */
    // public $host;
}
