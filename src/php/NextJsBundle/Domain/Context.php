<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context as OriginalContext;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Locale;

use Frontastic\Common\ReplicatorBundle\Domain\Customer as OriginalCustomer;
use Frontastic\Common\ReplicatorBundle\Domain\Project as OriginalProject;
use Kore\DataObject\DataObject;

/**
 * Stripped down version of {@link OriginalContext}
 */
class Context extends DataObject
{
    /**
     * Result of {@link OriginalContext.applicationEnvironment()}
     * @var string
     */
    public string $environment = 'production';

    /**
     * @required
     * @fixme should we have that as dedicated requestable data?
     * @fixme This struct does actually not contain any relevant data, except maybe customer based config which needs to be migrated to studio anyway.
     */
    // public OriginalCustomer $customer;

    /**
     * @required
     * @fixme should we have that as dedicated requestable data?
     */
    public OriginalProject $project;

    /**
     * @required
     */
    public array $projectConfiguration = [];

    /**
     * @required
     * @todo We will complete the defaults in our PHP code so no need to transmit the schema
     */
    // public $projectConfigurationSchema = [];

    /**
     * @required
     * @todo Needs to be the fully fledged locale encoded as a properly built locale string
     *       `language[_territory[.codeset]][@modifier]`
     */
    public string $locale;

    /**
     * Session is now a dedicated object that contains arbitrary information
     * @var Session
     */
    // public $session = null;

    /**
     * @var [string => bool]
     */
    public array $featureFlags = [];

    /**
     * Host can now be obtained by retrieving the Request
     *
     * @var string
     */
    // public $host;
}
