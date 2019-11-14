<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextDecorator;
use Frontastic\Catwalk\ApiCoreBundle\Domain\DataRepository;

class FeatureFlagService implements ContextDecorator
{
    private $repository;

    public function __construct(DataRepository $repository = null)
    {
        $this->repository = $repository;
    }

    /**
     * * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function decorate(Context $context): Context
    {
        // Missing feature flag custom app
        if ($this->repository === null) {
            return $context;
        }

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
            switch ($context->environment) {
                case 'dev':
                case 'development':
                    $flag = isset($featureFlag->onDevelopment) ? $featureFlag->onDevelopment : $featureFlag->on;
                    break;

                case 'stage':
                case 'staging':
                    $flag = isset($featureFlag->onStaging) ? $featureFlag->onStaging : $featureFlag->on;
                    break;

                default:
                    $flag = $featureFlag->on;
            }

            $context->featureFlags[$featureFlag->key] = (bool) $flag;
        }

        return $context;
    }
}
