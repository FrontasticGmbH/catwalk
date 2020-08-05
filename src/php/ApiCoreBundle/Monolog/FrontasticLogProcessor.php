<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Monolog;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Adds the requests frontastic_request_id to the log record.
 */
class FrontasticLogProcessor
{
    private const ATTRIBUTE_KEY = '_frontastic_request_id';
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

        if (!$request->attributes->has(self::ATTRIBUTE_KEY)) {
            return $record;
        }

        $record['extra'][self::LOG_KEY] = $request->attributes->get(self::ATTRIBUTE_KEY);
        $record['extra']['request'] = [
            'path' => $request->getPathInfo(),
            'host' => $request->getHost(),
        ];


        return $record;
    }
}
