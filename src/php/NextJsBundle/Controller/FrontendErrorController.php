<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles errors reported from the frontend
 */
class FrontendErrorController
{
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function indexAction(SymfonyRequest $request): JsonResponse
    {
        // Try to keep this method as resilient as possible, which also means
        // to keep the amount of dependencies as minimal as possible.
        $error = json_decode($request->getContent(), true);

        if (!$error) {
            return new JsonResponse([
                "error" => "nothing to log"
            ], Response::HTTP_BAD_REQUEST);
        }

        $message = $error['message'] ?? 'Unknown Frontend Error';
        $error['browser'] = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Browser';
        $error['stack'] = array_slice(
            array_map('trim', preg_split('(\r\n|\r|\n)', $error['stack'] ?? '')),
            0
        );
        $error['logSource'] = 'catwalk-php';

        $this->logger->error($message, $error);

        return new JsonResponse([
            "error" => $error,
            "message" => $message
        ]);
    }
}
