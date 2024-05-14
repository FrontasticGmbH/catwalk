<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Templating\EngineInterface;

class Http2LinkListener
{
    private string $appDir;

    private array $allowedExtensions = [
        'js',
        'css',
        'svg',
        'jpg',
        'gif',
        'png',
        'jpeg',
        'webp',
        'woff2',
        'woff',
        'ttf',
        'eot',
        'otf',
    ];

    public function __construct(string $appDir)
    {
        $this->appDir = $appDir;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // Don't do anything if it's not the master request
            return;
        }

        $acceptableContentTypes = $event->getRequest()->getAcceptableContentTypes();
        if (
            !in_array('text/html', $acceptableContentTypes) ||
            $event->getRequest()->isXmlHttpRequest()
        ) {
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
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    return in_array($extension, $this->allowedExtensions, true);
                }
            ))
        );

        header('Link: ' . implode(', ', $filesToPush));
    }
}
