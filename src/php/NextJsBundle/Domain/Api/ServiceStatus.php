<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Represents the status of a service
 */
class ServiceStatus extends DataObject
{

    /**
     * @var string The status of the service
     */
    public string $status;

    /**
     * @var bool Is the service up and running?
     */
    public bool $up;

    /**
     * @var float The response time of the service in milliseconds
     */
    public float $responseTimeMs;
}
