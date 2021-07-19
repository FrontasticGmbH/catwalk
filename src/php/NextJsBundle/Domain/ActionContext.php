<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

/**
 * This class only represents the information available, the TypeScript API should provide a mechanism to request each of these fields.
 */
class ActionContext
{
    public ?Context $frontasticContext = null;
}
