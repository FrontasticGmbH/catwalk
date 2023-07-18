<?php

declare(strict_types=1);

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VerifyStaticPagePathLocaleMatchesRequestLocale
{
    private ContextService $contextService;

    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();

        $routeName = $request->attributes->get('_route');
        $routeParameters = $request->attributes->get('_route_params');

        if (!is_string($routeName) || !str_starts_with($routeName, 'node_')) {
            return;
        }

        if (!is_array($routeParameters) || !array_key_exists('_frontastic_matching_locales', $routeParameters)) {
            return;
        }
        $matchingLocales = $routeParameters['_frontastic_matching_locales'];
        if (!is_array($matchingLocales) || count($matchingLocales) <= 0) {
            return;
        }

        $context = $this->contextService->createContextFromRequest($request);
        if (!in_array($context->locale, $matchingLocales, true)) {
            throw new NotFoundHttpException(
                sprintf(
                    'The requested node route %s only matches the locales %s but not the users locale %s',
                    $routeName,
                    implode(', ', $matchingLocales),
                    $context->locale,
                )
            );
        }
    }
}
