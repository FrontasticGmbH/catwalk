<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Controller\ValueResolver;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Resolves controller arguments type-hinted with {@see Context}.
 *
 * This is the native Symfony replacement for the former Sensio
 * FrameworkExtraBundle "ContextConverter" param converter. It reproduces the
 * exact behavior the converter had under `auto_convert: true`:
 *
 *  - it only acts on arguments type-hinted with the concrete {@see Context}
 *    class (including the nullable `?Context` form);
 *  - if the request already carries a {@see Context} instance under the
 *    argument's name (e.g. set by another listener), that instance is reused
 *    instead of building a new one;
 *  - otherwise a fresh Context is built from the request via
 *    {@see ContextService::createContextFromRequest()}.
 */
class ContextValueResolver implements ValueResolverInterface
{
    private ContextService $contextService;

    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    /**
     * @return iterable<Context>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // ArgumentMetadata::getType() returns the bare class name for both
        // `Context` and `?Context`, so nullable arguments are covered too.
        if ($argument->getType() !== Context::class) {
            return [];
        }

        // Preserve the converter's skip-if-already-set semantics: reuse an
        // existing Context instance carried under the argument's name.
        if ($request->attributes->has($argument->getName())) {
            $existing = $request->attributes->get($argument->getName());
            if ($existing instanceof Context) {
                return [$existing];
            }
        }

        return [$this->contextService->createContextFromRequest($request)];
    }
}
