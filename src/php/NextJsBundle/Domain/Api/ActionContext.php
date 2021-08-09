<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

/**
 * This class only represents the information available, the TypeScript API should provide a mechanism to request each
 * of these fields and we only submit those which have been requested.
 */
class ActionContext
{
    public ?Context $frontasticContext = null;
}
