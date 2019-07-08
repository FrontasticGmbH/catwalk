<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Templating\EngineInterface;

class Http2LinkListener
{
    private $appDir;

    public function __construct(string $appDir)
    {
        $this->appDir = $appDir;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // Don't do anything if it's not the master request
            return;
        }

        $acceptableContentTypes = $event->getRequest()->getAcceptableContentTypes();
        if (!in_array('text/html', $acceptableContentTypes) ||
            $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        $appDir = $this->appDir;
        $filesToPush = array_map(
            function (string $file) use ($appDir): string {
                return sprintf(
                    '<%s>; rel=%s; as=%s',
                    str_replace($appDir . '/public', '', $file),
                    'preload',
                    strpos($file, '.css') ? 'style' : 'script'
                );
            },
            array_values(array_filter(
                glob($this->appDir . '/public/assets/*/*main*'),
                function (string $file): bool {
                    return !strpos($file, '.map');
                }
            ))
        );

        header('Link: ' . implode(', ', $filesToPush));
    }
}
