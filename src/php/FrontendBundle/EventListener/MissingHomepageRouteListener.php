<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Templating\EngineInterface;
use Twig\Environment;

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

    public function __construct(Environment $templating, bool $debug = false)
    {
        $this->debug = $debug;
        $this->templating = $templating;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        if (!$this->debug || !($e = $event->getThrowable()) instanceof NotFoundHttpException) {
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
