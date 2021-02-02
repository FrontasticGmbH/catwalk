<?php
namespace Kameleoon\Data;

class PageView implements DataInterface
{
    private $url;
    private $title;
    private $referrer;
    private $nonce;

    public function __construct($url, $title, $referrer)
    {
        $this->url = $url;
        $this->title = $title;
        $this->referrer = $referrer;
        $this->nonce = \Kameleoon\KameleoonClientImpl::obtainNonce();
    }

    public function obtainFullPostTextLine ()
    {
        return "eventType=page&href=" . $this->url . "&title=" . $this->title . "&keyPages=[]" . ($this->referrer == null ? "" : "&referrers=[" . $this->referrer . "]") . "&nonce=" . $this->nonce;
    }
}

?>