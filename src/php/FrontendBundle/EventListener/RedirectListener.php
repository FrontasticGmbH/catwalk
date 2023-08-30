<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\Domain\RedirectService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedirectListener
{
    /**
     * @var RedirectService
     */
    private $redirectService;

    /**
     * @var ContextService
     */
    private $contextService;

    public function __construct(RedirectService $redirectService, ContextService $contextService)
    {
        $this->redirectService = $redirectService;
        $this->contextService = $contextService;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->isNotFoundException($event) || !$event->isMasterRequest()) {
            return;
        }

        $redirect = $this->redirectService->getRedirectUrlForRequest(
            $event->getRequest()->getPathInfo(),
            $event->getRequest()->query,
            $this->contextService->createContextFromRequest($event->getRequest())
        );

        if ($redirect === null) {
            return;
        }

        $event->setResponse(new RedirectResponse($redirect->target, $redirect->statusCode));
    }

    protected function isNotFoundException(ExceptionEvent $event): bool
    {
        return $event->getThrowable() instanceof NotFoundHttpException;
    }
}
