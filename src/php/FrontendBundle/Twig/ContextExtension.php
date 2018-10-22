<?php

namespace Frontastic\Catwalk\FrontendBundle\Twig;

use Symfony\Component\HttpFoundation\Request;

use Frontastic\Common\JsonSerializer;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;

class ContextExtension extends \Twig_Extension
{
    /**
     * @var ContextService
     */
    private $contextService;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    public function __construct(ContextService $contextService, JsonSerializer $jsonSerializer)
    {
        $this->contextService = $contextService;
        $this->jsonSerializer = $jsonSerializer;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('frontastic_context', [$this, 'getContext']),
        ];
    }

    public function getContext()
    {
        return $this->jsonSerializer->serialize(
            $this->contextService->createContextFromRequest()
        );
    }
}
