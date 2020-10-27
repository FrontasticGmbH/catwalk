<?php
namespace Kameleoon\Data;

class Custom implements DataInterface
{
    private $id;
    private $value;
    private $nonce;

    public function __construct($id, $value)
    {
        $this->id = $id;
        $this->value = $value;
        $this->nonce = \Kameleoon\KameleoonClientImpl::obtainNonce();
    }

    public function obtainFullPostTextLine()
    {
        $encoded = rawurlencode(json_encode(array(array($this->value, 1))));
        return "eventType=customData&index=" . $this->id . "&valueToCount=" . $encoded . "&overwrite=true&nonce=" . $this->nonce;
    }
}

?>