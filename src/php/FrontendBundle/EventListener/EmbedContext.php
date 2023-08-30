<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Twig\NodeExtension;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class EmbedContext
{
    private $nodeExtension;

    public function __construct(NodeExtension $nodeExtension)
    {
        $this->nodeExtension = $nodeExtension;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->getRequest()->get('_embed')) {
            return;
        }

        $response = $event->getResponse();
        if (!$response instanceof JsonResponse) {
            return;
        }

        $response->setData(
            $this->nodeExtension->completeInformation(
                json_decode($response->getContent(), true)
            )
        );
    }
}
