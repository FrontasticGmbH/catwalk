<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Kore\DataObject\DataObject;

class RegisterPaymentResult extends DataObject
{
    public const PAYMENT_MODE_SEAMLESS = 'seamless';
    public const PAYMENT_MODE_EMBEDDED = 'embedded';

    /** @var string */
    public $paymentUrl;

    /** @var string self::PAYMENT_MODE_* */
    public $paymentMode;
}
