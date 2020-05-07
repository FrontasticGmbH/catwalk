<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\EventListener\ErrorHandler\ErrorNodeRenderer;
use Symfony\Component\Console\EventListener\ErrorListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This should replace symfonys ErrorListener
 *
 * We do this, because the Error Controller in standard symfony does not receive the full exception,
 * but rather a FlattenException. While this is great for symfony as a framework,
 * we want to be able to access information of the exception, like the $statusCode in the HttpExceptionInterface.
 *
 * @see HttpExceptionInterface
 * @see ErrorListener
 */
class ErrorHandler implements EventSubscriberInterface
{
    /**
     * @var ContextService
     */
    private $contextService;

    /**
     * @var ErrorNodeRenderer
     */
    private $errorNodeRenderer;

    public function __construct(
        ContextService $contextService,
        ErrorNodeRenderer $errorNodeRenderer
    ) {
        $this->contextService = $contextService;
        $this->errorNodeRenderer = $errorNodeRenderer;
    }

    public function getResponseForErrorEvent(ExceptionEvent $event)
    {
        $context = $this->contextService->createContextFromRequest($event->getRequest());

        $event->setResponse(
            new Response(
                $this->errorNodeRenderer->renderErrorNode(
                    $context,
                    $event->getThrowable()
                ),
                $this->getStatusCode($event)
            )
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['getResponseForErrorEvent', -100],
            ],
        ];
    }


    private function getStatusCode(ExceptionEvent $event): int
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof HttpExceptionInterface) {
            return $throwable->getStatusCode();
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
