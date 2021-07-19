<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Kore\DataObject\DataObject;

class DynamicPageContext extends DataObject
{
    public ?Context $frontasticContext = null;

    public ?array $sessionData = null;
}
