<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\RemoteDecorator;

use Kore\DataObject\DataObject;

class Endpoint extends DataObject
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $format = 'json';

    /**
     * @var string
     */
    public $method = 'POST';

    /**
     * @var int
     */
    public $priority = 0;

    /**
     * Allowed time for requests in seconds
     *
     * @var float
     */
    public $timeout = 0.1;

    /**
     * If the endpoint fails to respond, shall the stream error (default) or
     * should we respond with the original value.
     *
     * @var bool
     */
    public $optional = false;

    /**
     * @var string[]
     */
    public $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
    ];
}
