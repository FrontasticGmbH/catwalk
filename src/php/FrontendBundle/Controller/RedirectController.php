<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\Redirect;
use Frontastic\Catwalk\FrontendBundle\Domain\RedirectService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectController extends Controller
{
    public function redirectAction(string $redirectId)
    {
        $redirectService = $this->get(RedirectService::class);

        $redirect = $redirectService->get($redirectId);

        switch ($redirect->targetType) {
            case Redirect::TARGET_TYPE_LINK:
                $targetUrl = $redirect->target;
                break;
            case Redirect::TARGET_TYPE_NODE:
                $targetUrl = $this->generateUrl('node_' . $redirect->target);
                break;
            default:
                throw new \InvalidArgumentException("Unknown redirect target type $redirect->targetType");
        }

        return new RedirectResponse($targetUrl, 301);
    }
}
