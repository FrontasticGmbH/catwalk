<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Controller\ParameterConverter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class ContextConverter implements ParamConverterInterface
{
    private $contextService;

    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $request->attributes->set(
            $configuration->getName(),
            $this->contextService->createContextFromRequest($request)
        );

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Context::class;
    }
}
