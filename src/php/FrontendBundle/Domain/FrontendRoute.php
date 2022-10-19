<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class FrontendRoute extends DataObject
{
    public ?int $frontendRouteId;

    public int $nodeId;

    public string $route;

    public string $locale;
}
