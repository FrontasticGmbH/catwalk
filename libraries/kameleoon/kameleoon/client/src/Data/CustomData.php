<?php
namespace Kameleoon\Data;

use Kameleoon\KameleoonClientImpl;

class CustomData implements DataInterface
{
    private $id;
    private $value;
    private $nonce;

    public function __construct($id, $value)
    {
        $this->id = $id;
        $this->value = $value;
        $this->nonce = KameleoonClientImpl::obtainNonce();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function obtainFullPostTextLine()
    {
        $encoded = rawurlencode(json_encode(array(array($this->value, 1)), JSON_UNESCAPED_UNICODE));
        return "eventType=customData&index=" . $this->id . "&valueToCount=" . $encoded . "&overwrite=true&nonce=" . $this->nonce;
    }
}