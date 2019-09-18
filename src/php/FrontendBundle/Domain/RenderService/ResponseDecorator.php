<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\RenderService;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ResponseDecorator
{
    const NO_STATUS_CODE = -23;

    /**
     * @var int
     */
    private $statusCode = self::NO_STATUS_CODE;

    public function setTimedOut()
    {
        if (self::NO_STATUS_CODE === $this->statusCode) {
            $this->statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        }
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpExceptionInterface) {
            $this->statusCode = $exception->getStatusCode();
        } else {
            $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        // No status changes required
        if (self::NO_STATUS_CODE === $this->statusCode) {
            return;
        }
        $response = $event->getResponse();

        // There is already an error status set
        if ($response->getStatusCode() >= 400) {
            return;
        }
        $response->setStatusCode($this->statusCode);
    }
}
