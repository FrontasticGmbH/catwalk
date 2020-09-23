<?php

interface Data
{
    public function obtainFullPostTextLine();
}

class Custom implements Data
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

    public function obtainFullPostTextLine()
    {
        return "eventType=customData&index=" . $this->id . "&valueToCount=[[" . $this->value . ", 1]]&overwrite=true&nonce=" . $this->nonce;
    }
}

class Browser implements Data {

    public static $browsers = array("CHROME"=>0, "INTERNET_EXPLORER"=>1, "FIREFOX"=>2, "SAFARI"=>3, "OPERA"=>4, "OTHER"=>5);
    private $browser;
    private $nonce;

    public function __construct($browser)
    {
        $this->browser = $browser;
        $this->nonce = KameleoonClientImpl::obtainNonce();
    }

    public function obtainFullPostTextLine()
    {
        return "eventType=staticData&browser=" . $this->browser . "&nonce=" . $this->nonce;
    }
}

class PageView implements Data
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
        $this->nonce = KameleoonClientImpl::obtainNonce();
    }

    public function obtainFullPostTextLine ()
    {
        return "eventType=page&href=" . $this->url . "&title=" . $this->title . "&keyPages=[]" . ($this->referrer == null ? "" : "&referrers=[" . $this->referrer . "]") . "&nonce=" . $this->nonce;
    }
}

class Interest implements Data
{
    private $index;
    private $fresh;
    private $nonce;

    public function __construct($index)
    {
        $this->index = $index;
        $this->nonce = KameleoonClientImpl::obtainNonce();
    }

    public function obtainFullPostTextLine () {
        return "eventType=interests&indexes=[" . $this->index . "]&fresh=true&nonce=" . $this->nonce;
    }
}

class Conversion implements Data
{
    private $goalId;
    private $revenue;
    private $negative;
    private $nonce;

    public function __construct($goalId, $revenue = NULL, $negative = NULL) {
        $this->goalId = $goalId;
        $this->revenue = $revenue == null ? 0 : $revenue;
        $this->negative = $negative == null ? false : $negative;
        $this->nonce = KameleoonClientImpl::obtainNonce();
    }

    public function obtainFullPostTextLine () {
        return "eventType=conversion&goalId=" . $this->goalId . "&revenue=" . $this->revenue . "&negative=" . $this->negative . "&nonce=" . $this->nonce;
    }
}

