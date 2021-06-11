<?php

namespace Frontastic\Catwalk\TrackingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;

class TrackingController extends AbstractController
{
    /**
     * @var string[]
     */
    private $trackablePageTypes = [
        'viewProduct',
        'viewProductListing',
        'startCheckout',
        'paymentPage',
    ];

    /**
     * @var TrackingService
     */
    private $trackingService;

    public function __construct(TrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    public function trackAction(Context $context, string $pageType): JsonResponse
    {
        if (!in_array($pageType, $this->trackablePageTypes)) {
            throw new \OutOfBoundsException(
                "$pageType is not trackable, options are: " .
                implode(', ', $this->trackablePageTypes)
            );
        }

        $method = 'reach' . ucfirst($pageType);
        $this->trackingService->$method($context);
        return new JsonResponse(['ok' => true]);
    }
}
