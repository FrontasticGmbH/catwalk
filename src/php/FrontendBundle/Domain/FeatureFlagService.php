<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextDecorator;
use Frontastic\Catwalk\ApiCoreBundle\Domain\DataRepository;

class FeatureFlagService implements ContextDecorator
{
    private $repository;

    public function __construct(DataRepository $repository)
    {
        $this->repository = $repository;
    }

    public function decorate(Context $context): Context
    {
        $featureFlags = $this->repository->findAll();
        foreach ($featureFlags as $featureFlag) {
            $context->featureFlags[$featureFlag->key] = (bool) $featureFlag->on;
        }

        return $context;
    }
}
