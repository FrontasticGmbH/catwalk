<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Context retrieved by the "dynamic-page-handler" extension.
 *
 * All fields in the context are optional. We want to introduce a mechanism in the future that allows extensions to
 * annotate which context data they require.
 *
 * @type
 */
class DynamicPageContext extends DataObject
{
    /**
     * @var Context
     */
    public ?Context $frontasticContext = null;
}
