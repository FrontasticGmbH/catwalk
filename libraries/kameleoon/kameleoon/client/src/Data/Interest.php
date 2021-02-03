<?php
namespace Kameleoon\Data;

class Interest implements DataInterface
{
    private $index;
    private $fresh;
    private $nonce;

    public function __construct($index)
    {
        $this->index = $index;
        $this->nonce = \Kameleoon\KameleoonClientImpl::obtainNonce();
    }

    public function obtainFullPostTextLine () {
        return "eventType=interests&indexes=[" . $this->index . "]&fresh=true&nonce=" . $this->nonce;
    }
}

?>