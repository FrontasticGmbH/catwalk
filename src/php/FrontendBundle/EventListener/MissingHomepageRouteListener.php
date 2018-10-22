<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Templating\EngineInterface;

class MissingHomepageRouteListener
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating, bool $debug = false)
    {
        $this->debug = $debug;
        $this->templating = $templating;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$this->debug || !($e = $event->getException()) instanceof NotFoundHttpException) {
            return;
        }

        if ($e->getPrevious() instanceof NoConfigurationException) {
            $event->setResponse($this->createWelcomeResponse());
        }
    }

    private function createWelcomeResponse(): Response
    {
        return new Response(
            $this->templating->render('@FrontasticCatwalkFrontend/missing_homepage.html.twig'),
            Response::HTTP_NOT_FOUND
        );
    }
}
