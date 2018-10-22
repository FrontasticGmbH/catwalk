<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Kore\DataObject\DataObject;

class AppRepository extends DataObject
{
    /**
     * @var string
     */
    public $app;

    /**
     * @var string
     */
    public $sequence;
}
