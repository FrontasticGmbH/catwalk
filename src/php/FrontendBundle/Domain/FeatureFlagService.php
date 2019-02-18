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
        try {
            $featureFlags = $this->repository->findAll();
        } catch (\Exception $e) {
            // If there is a critical error, like the database not existing
            // yet, just ignore it and provide no feature flags.
            //
            // This might make this hard to debug but since this happens during
            // context initialization we should be very defensive.
            $featureFlags = [];
        }

        foreach ($featureFlags as $featureFlag) {
            $context->featureFlags[$featureFlag->key] = (bool) $featureFlag->on;
        }

        return $context;
    }
}
