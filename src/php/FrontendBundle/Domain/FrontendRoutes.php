<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class FrontendRoutes extends DataObject
{
    public int $frontendRoutesId;

    public array $frontendRoutes = [];
}
