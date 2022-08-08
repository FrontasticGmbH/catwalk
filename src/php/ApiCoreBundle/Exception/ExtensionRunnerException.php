<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Exception;

class ExtensionRunnerException extends \Exception
{
    private array $context;

    public function __construct(string $message, $code = 0, Throwable $previous = null, array $context = [])
    {
        $this->context = $context;
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
