<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Exception\DeprecationException;

/**
 * Throws an exception in development if you want to retrieve the Context from the container.
 *
 * Retrieving the Context trough the Container should not have been possible in the first place. We removed all cases
 * where this is required in the Catwalk core. This service provides a legacy way to retrieve the Context from the
 * Container in production and throws an exception in development.
 */
class ContextInContainerDeprecationProvider
{
    /**
     * @var ContextService
     */
    private $contextService;

    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    public function provideContext(): Context
    {
        $context = $this->contextService->createContextFromRequest();

        if ($context->environment !== 'prod') {
            throw DeprecationException::withSuggestion(
                'Context retrieved from Container',
                'Retrieve only Project from Container or get ContextService injected to create Context properly'
            );
        }

        return $context;
    }
}
