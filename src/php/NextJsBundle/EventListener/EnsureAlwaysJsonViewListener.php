<?php

namespace Frontastic\Catwalk\NextJsBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Frontastic\Common\JsonSerializer;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class EnsureAlwaysJsonViewListener
{
    /**
     * @var \Frontastic\Common\JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @param string $engine
     */
    public function __construct(JsonSerializer $jsonSerializer)
    {
        $this->jsonSerializer = $jsonSerializer;
    }

    public function onKernelView(ViewEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->attributes->has('_controller')) {
            return;
        }

        $controller = $request->attributes->get('_controller');
        $result = $event->getControllerResult();

        if (!$controller || $result instanceof Response) {
            return;
        }

        $event->setResponse(new JsonResponse($this->jsonSerializer->serialize($result)));
    }
}
