<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Frontastic context that an API hub server is running in (project & environment).
 *
 * Includes environment information configuration on ba
 *
 * @replaces Frontastic\Catwalk\ApiCoreBundle\Domain\Context
 * @type
 */
class Context extends DataObject
{
    /**
     * One of "production", "staging" or "development".
     *
     * @internal Result of {@link Frontastic\Catwalk\ApiCoreBundle\Domain\Context.applicationEnvironment()}
     * @required
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
     * Additional project configuration from Frontastic studio.
     *
     * TODO: This is not completed right now.
     *
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
     * The currently set locale by the user in the frontend.
     *
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
     * Feature flags mapped to their state.
     *
     * @required
     * @var array<string, bool>
     */
    public array $featureFlags = [];

    /**
     * @removed Host can now be obtained by retrieving the Request
     */
    // public $host;
}
