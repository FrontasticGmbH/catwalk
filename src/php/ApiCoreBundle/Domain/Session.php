<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Kore\DataObject\DataObject;

class Session extends DataObject
{
    /**
     * @var string
     */
    public $user = null;

    /**
     * @var bool
     */
    public $loggedIn = false;
}
