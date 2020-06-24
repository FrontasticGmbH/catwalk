<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Frontastic\Common\HttpClient\Response;

class WirecardResponseException extends \RuntimeException
{
    /** @var Response */
    private $response;

    public function __construct(string $message, Response $response)
    {
        parent::__construct($message);
        $this->response = $response;
    }
}
