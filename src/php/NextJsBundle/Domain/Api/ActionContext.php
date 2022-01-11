<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * This class only represents the information available, the TypeScript API should provide a mechanism to request each
 * of these fields and we only submit those which have been requested.
 * @type
 */
class ActionContext extends DataObject
{
    /**
     * @var Context
     */
    public ?Context $frontasticContext = null;
}
