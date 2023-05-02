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
    private $applicationEnvironment;


    public function __construct(RequestStack $requestStack, string $applicationEnvironment)
    {
        $this->requestStack = $requestStack;
        $this->applicationEnvironment = $applicationEnvironment;
    }

    public function __invoke(array $record)
    {
        $environment = $this->applicationEnvironment;

        $request = $this->requestStack->getMasterRequest();

        if (in_array($environment, ['prod', 'production', 'staging', 'stag'])) {
            if ($this->shouldFilterOutLogMessage($record['message'] ?? '')) {
                //Set the level high so it doesn't get logged
                $record['level'] = 1000;
                return $record;
            }
        }


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

    private function shouldFilterOutLogMessage(string $message): bool
    {
        // We can add more keywords we want to filter out here
        $filterKeywords = [
            'Matched route'
        ];

        foreach ($filterKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}
