<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ContentSecurityPolicy
{
    const BASELINE = [
        'default-src' => [
            'self',
        ],
        'script-src' => [
            'self',
            'unsafe-inline',
            'unsafe-eval',
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
        ],
        'frame-ancestors' => [
            'self',
            'https://*.frontastic.io',
            'frontastic.io.local',
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

    const DEFAULT = [
        'script-src' => [
            'https://ssl.google-analytics.com',
            'https://connect.facebook.net',
        ],
        'frame-src' => [
            'https://www.facebook.com',
            'https://s-static.ak.facebook.com',
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

    public function onKernelResponse(ResponseEvent $event)
    {
        $responseHeaders = $event->getResponse()->headers;
        $policies = $this->merge(
            self::BASELINE,
            $this->project->configuration['policy'] ?? self::DEFAULT
        );

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
                                    array_unique($values)
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

    private function merge($policy, $additions): array
    {
        return array_merge_recursive($policy, $additions);
    }
}
