<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Project as OriginalProject;

use Kore\DataObject\DataObject;

/**
 * @replaces Frontastic\Catwalk\ApiCoreBundle\Domain\Context
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
     * @fixme should we have that as dedicated requestable data? Is probably needed in every call anyway to obtain credentials.
     */
    public OriginalProject $project;

    /**
     * @required
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
     */
    public string $locale;

    /**
     * @removed $sessionData is now an arbitrary hash-map and available as dedicatedly requestable
     */
    // public $session = null;

    /**
     * @var [string => bool]
     */
    public array $featureFlags = [];

    /**
     * @removed Host can now be obtained by retrieving the Request
     */
    // public $host;
}
