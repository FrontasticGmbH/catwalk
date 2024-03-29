<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Monolog;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Adds the requests frontastic_request_id to the log record.
 */
class FrontasticLogProcessor
{
    private const LOG_KEY = 'requestId';

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function __invoke(array $record)
    {
        $request = $this->requestStack->getMasterRequest();

        if (!$request instanceof Request) {
            // There might be no master request, for instance for console commands.
            return $record;
        }

        if (!$request->attributes->has(RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY)) {
            return $record;
        }

        $record['extra'][self::LOG_KEY] = $request->attributes->get(RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY);
        $record['extra']['request'] = [
            'path' => $request->getPathInfo(),
            'host' => $request->getHost(),
        ];


        return $record;
    }
}
