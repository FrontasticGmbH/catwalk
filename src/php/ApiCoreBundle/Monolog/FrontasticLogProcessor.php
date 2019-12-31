<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Monolog;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Adds the requests frontastic_request_id to the log record.
 */
class FrontasticLogProcessor
{
    private const ATTRIBUTE_KEY = 'frontastic_request_id';
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

        // It might make sense to move this to the top level record.
        // I decided to keep it here for now, because that makes it visible in the plain text logfiles.
        $record['extra']['frontastic_request_id'] = $request->attributes->get(self::ATTRIBUTE_KEY);

        return $record;
    }
}
