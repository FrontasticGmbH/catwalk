<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\FrontendBundle\Domain\RedirectService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedirectListener
{
    /**
     * @var RedirectService
     */
    private $redirectService;

    public function __construct(RedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        if (!$this->isNotFoundException($event) || !$event->isMasterRequest()) {
            return;
        }

        $targetUrl = $this->redirectService->getRedirectUrlForRequest(
            $event->getRequest()->getPathInfo(),
            $event->getRequest()->query
        );
        if ($targetUrl === null) {
            return;
        }

        $event->setResponse(new RedirectResponse($targetUrl, 301));
    }

    protected function isNotFoundException(GetResponseForExceptionEvent $event): bool
    {
        return $event->getException() instanceof NotFoundHttpException;
    }
}
