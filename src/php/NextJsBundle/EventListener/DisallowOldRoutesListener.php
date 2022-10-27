<?php

namespace Frontastic\Catwalk\NextJsBundle\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DisallowOldRoutesListener
{
    private const ALLOWED_ROUTES = [
        'Frontastic.Frontend.Preview.store',
        'Frontastic.Frontend.Preview.view',
        'Frontastic.ApiCoreBundle.Api.endpoint',
        'Frontastic.ApiCoreBundle.Api.version',
        'Frontastic.ApiCoreBundle.Api.clear'
    ];

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $routeName = $request->get('_route');

        if (!$routeName || $this->isRouteAllowed($routeName)) {
            return;
        }

        $msg = $request->getMethod() . " " .  $request->getPathInfo();
        throw new NotFoundHttpException("No route found for \"$msg\"");
    }

    private function isRouteAllowed($route)
    {
        return str_contains($route, 'Frontastic.NextJs.Api') || in_array($route, self::ALLOWED_ROUTES);
    }
}
