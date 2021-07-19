<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * This class only represents the information available, the TypeScript API should provide a mechanism to request each
 * of these fields and we only submit those which have been requested.
 */
class DynamicPageContext extends DataObject
{
    public ?Context $frontasticContext = null;
}
