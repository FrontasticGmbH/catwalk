<?php

namespace Frontastic\Catwalk\NextJsBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use Frontastic\Common\CoreBundle\Domain\ErrorResult;
use Frontastic\Common\Translatable;

class EnsureAlwaysJsonExceptionListener
{
    /**
     * @var bool
     */
    private $debug;

    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $errorData = [
            'message' => $exception->getMessage(),
        ];

        if ($exception instanceof Translatable) {
            $errorData['code'] = $exception->getTranslationCode();
            $errorData['parameters'] = $exception->getTranslationParameters();
        }

        if ($this->debug) {
            $errorData['file'] = $exception->getFile();
            $errorData['line'] = $exception->getLine();
            $errorData['stack'] = explode(PHP_EOL, $exception->getTraceAsString());
        }

        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
        $event->setResponse(new JsonResponse(new ErrorResult($errorData), $statusCode));
    }
}
