<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProxyController extends Controller
{
    private $hostWhitelist = [
        'www.frontastic.cloud',
    ];

    public function indexAction(Request $request)
    {
        if (!$request->query->has('url')) {
            throw new BadRequestHttpException();
        }

        // TODO: Enhance to support any kind of request and maintain Cookies by passing them to the client
        // We need to ensure to not expose and FRONTASTIC cookies in this place

        $url = $request->query->get('url');

        if ($this->isNotWhitelisted($url)) {
            throw new BadRequestHttpException();
        }

        $guzzle = new \GuzzleHttp\Client();
        $response = $guzzle->request('GET', $url);

        $httpFactory = new HttpFoundationFactory();
        return $httpFactory->createResponse($response);
    }

    private function isNotWhitelisted(string $url): bool
    {
        $urlParts = parse_url($url);

        return !in_array($urlParts['host'], $this->hostWhitelist);
    }
}
