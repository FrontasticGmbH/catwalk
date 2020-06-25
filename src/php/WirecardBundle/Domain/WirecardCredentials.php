<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Kore\DataObject\DataObject;

class WirecardCredentials extends DataObject
{
    /** @var string */
    public $merchant;

    /** @var string */
    public $user;

    /** @var string */
    public $password;

    /** @var string */
    public $secret;

    /** @var string */
    public $host;

    /** @var string|null */
    public $creditorId;
}
