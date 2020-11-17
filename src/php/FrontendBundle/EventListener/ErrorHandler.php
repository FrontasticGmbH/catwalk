<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\EventListener\ErrorHandler\ErrorNodeRenderer;
use Symfony\Component\Console\EventListener\ErrorListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $request = $event->getRequest();

        $route = $request->get('_route');
        if ($route !== null && !$this->isMaster($route) && !$this->isNode($route)) {
            // return as we are obviously having an API request here and our JSON Response handler will take care of
            // dealing with the Exception
            return;
        }

        $context = $this->contextService->createContextFromRequest($request);

        $acceptableContentTypes = $request->getAcceptableContentTypes();
        if (!in_array('application/json', $acceptableContentTypes) &&
            !in_array('text/json', $acceptableContentTypes) &&
            !$request->isXmlHttpRequest()) {
            $response = new Response(
                $this->errorNodeRenderer->renderErrorNode(
                    $context,
                    $event->getThrowable()
                ),
                $this->getStatusCode($event)
            );
        } else {
            $response = new JsonResponse(
                $this->errorNodeRenderer->getViewData(
                    $context,
                    $event->getThrowable()
                ),
                $this->getStatusCode($event)
            );
        }

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['getResponseForErrorEvent', 5],
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

    private function isMaster($route): bool
    {
        return strpos($route, '.Frontend.Master.') !== false;
    }

    private function isNode($route): bool
    {
        return strpos($route, 'node_') === 0;
    }
}
