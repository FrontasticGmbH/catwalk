<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Kore\DataObject\DataObject;

class WirecardCredentials extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $merchant;

    /**
     * @var string
     * @required
     */
    public $user;

    /**
     * @var string
     * @required
     */
    public $password;

    /**
     * @var string
     * @required
     */
    public $secret;

    /**
     * @var string
     * @required
     */
    public $host;

    /**
     * @var string|null
     */
    public $creditorId;
}
