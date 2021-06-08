<?php

namespace Frontastic\Catwalk\TrackingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;

class TrackingController extends Controller
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

    public function trackAction(Context $context, string $pageType): JsonResponse
    {
        $trackingService = $this->get(TrackingService::class);

        if (!in_array($pageType, $this->trackablePageTypes)) {
            throw new \OutOfBoundsException(
                "$pageType is not trackable, options are: " .
                implode(', ', $this->trackablePageTypes)
            );
        }

        $method = 'reach' . ucfirst($pageType);
        $trackingService->$method($context);
        return new JsonResponse(['ok' => true]);
    }
}
