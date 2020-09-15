<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Frontastic\Common\CartApiBundle\Domain\Cart;
use Kore\DataObject\DataObject;

class RegisterPaymentCommand extends DataObject
{
    public const TYPE_CREDITCARD = 'creditcard';

    /**
     * @var string
     * @required
     */
    public $paymentId;

    /**
     * @var int
     * @required
     */
    public $amount;

    /**
     * @var Cart
     * @required
     */
    public $cart;

    /**
     * @var string
     * @required
     */
    public $type;

    /**
     * @var ?string
     * @required
     */
    public $credentialsType;

    public function getCredentialsType(): string
    {
        return $this->credentialsType ?? $this->type;
    }
}
