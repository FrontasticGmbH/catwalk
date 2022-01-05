<?php

namespace Frontastic\Catwalk\NextJsBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
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

    public function onKernelException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();

        $errorData = [
            'message' => $throwable->getMessage(),
        ];

        if ($throwable instanceof Translatable) {
            $errorData['code'] = $throwable->getTranslationCode();
            $errorData['parameters'] = $throwable->getTranslationParameters();
        }

        if ($this->debug) {
            $errorData['file'] = $throwable->getFile();
            $errorData['line'] = $throwable->getLine();
            $errorData['stack'] = explode(PHP_EOL, $throwable->getTraceAsString());
        }

        $statusCode = $throwable instanceof HttpExceptionInterface ? $throwable->getStatusCode() : 500;
        $event->setResponse(new JsonResponse(new ErrorResult($errorData), $statusCode));
    }
}
