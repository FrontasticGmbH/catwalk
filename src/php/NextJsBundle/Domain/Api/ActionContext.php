<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Context retrieved by an "action" extension.
 *
 * All fields in the context are optional. We want to introduce a mechanism in the future that allows extensions to
 * annotate which context data they require.
 *
 * @type
 */
class ActionContext extends DataObject
{
    /**
     * @var Context
     */
    public ?Context $frontasticContext = null;
}
