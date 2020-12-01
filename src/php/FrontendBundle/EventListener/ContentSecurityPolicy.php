<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class ContentSecurityPolicy
{
    const DEFAULT = [
        'default-src' => [
            'self',
        ],
        'script-src' => [
            'self',
            'unsafe-inline',
            'unsafe-eval',
            'https://ssl.google-analytics.com',
            'https://connect.facebook.net',
        ],
        'img-src' => [
            'self',
            'data: *',
        ],
        'style-src' => [
            'self',
            'unsafe-inline',
            'https://fonts.googleapis.com',
        ],
        'font-src' => [
            'self',
            'https://themes.googleusercontent.com',
        ],
        'frame-src' => [
            'self',
            'https://www.facebook.com',
            'https://s-static.ak.facebook.com',
        ],
        'object-src' => [
            'self',
        ],
        'connect-src' => [
            'self',
            'ws:',
            'wss:',
        ],
    ];

    /**
     * @var Project
     */
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $responseHeaders = $event->getResponse()->headers;
        $policies = $this->project->configuration['policy'] ?? self::DEFAULT;

        $responseHeaders->set(
            'Content-Security-Policy',
            implode(
                '; ',
                array_map(
                    function (string $type, array $values): string {
                        return sprintf(
                            '%s %s',
                            $type,
                            implode(
                                ' ',
                                array_map(
                                    function (string $target): string {
                                        if (in_array($target, ['self', 'unsafe-inline', 'unsafe-eval'])) {
                                            return "'" . $target . "'";
                                        }

                                        return $target;
                                    },
                                    $values
                                )
                            )
                        );
                    },
                    array_keys($policies),
                    $policies
                )
            )
        );
    }
}
