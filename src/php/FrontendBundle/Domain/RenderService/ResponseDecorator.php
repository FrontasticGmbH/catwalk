<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\RenderService;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * If SSR has a timeout, this service is informed and makes sure we respond with a 503.
 *
 * This is important for SEO, otherwise the pages without SSR might end up in search engine indexes.
 */
class ResponseDecorator
{
    /**
     * @var ?int
     */
    private $statusCode = null;

    public function setTimedOut()
    {
        if ($this->statusCode === null) {
            $this->statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        // No status changes required
        if ($this->statusCode === null) {
            return;
        }

        $response = $event->getResponse();
        $response->setStatusCode($this->statusCode);
    }
}
