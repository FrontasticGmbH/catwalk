<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\EventListener\ErrorHandler\ErrorNodeRenderer;
use Frontastic\Common\JsonSerializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorHandler
{
    /**
     * @var ContextService
     */
    private $contextService;

    /**
     * @var ErrorNodeRenderer
     */
    private $errorNodeRenderer;

    private JsonSerializer $jsonSerializer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(
        ContextService $contextService,
        ErrorNodeRenderer $errorNodeRenderer,
        JsonSerializer $jsonSerializer,
        LoggerInterface $logger
    ) {
        $this->contextService = $contextService;
        $this->errorNodeRenderer = $errorNodeRenderer;
        $this->logger = $logger;
        $this->jsonSerializer = $jsonSerializer;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $this->logger->error(
            'Error handler: {message}',
            [
                'message' => $event->getThrowable()->getMessage(),
                'exception' => $event->getThrowable(),
            ]
        );

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
                $this->jsonSerializer->serialize(
                    $this->errorNodeRenderer->getViewData(
                        $context,
                        $event->getThrowable()
                    ),
                ),
                $this->getStatusCode($event)
            );
        }

        $event->setResponse($response);
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
