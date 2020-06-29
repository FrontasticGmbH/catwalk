<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Frontastic\Common\CartApiBundle\Domain\Cart;
use Kore\DataObject\DataObject;

class RegisterPaymentCommand extends DataObject
{
    public const TYPE_CREDITCARD = 'creditcard';

    /** @var string */
    public $paymentId;

    /** @var int */
    public $amount;

    /** @var Cart */
    public $cart;

    /** @var string self::TYPE_* */
    public $type;

    /** @var string|null */
    public $credentialsType;

    public function getCredentialsType(): string
    {
        return $this->credentialsType ?? $this->type;
    }
}
