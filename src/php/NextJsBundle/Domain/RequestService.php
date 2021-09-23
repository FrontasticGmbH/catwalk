<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RequestService
{
    /**
     * @param SymfonyRequest $request
     * @return Request
     */
    public function createApiRequest(SymfonyRequest $request): Request
    {
        $apiRequest = new Request();
        $apiRequest->query = (object)($request->query->getIterator()->getArrayCopy());
        $apiRequest->path = $request->getPathInfo();
        $apiRequest->body = $request->getContent();
        $apiRequest->cookies = (object)($request->cookies->all());
        return $apiRequest;
    }
}
