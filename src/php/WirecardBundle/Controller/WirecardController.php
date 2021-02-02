<?php

namespace Frontastic\Catwalk\WirecardBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\WirecardBundle\Domain\RegisterPaymentCommand;
use Frontastic\Catwalk\WirecardBundle\Domain\WirecardService;
use Frontastic\Catwalk\FrontendBundle\Controller\CartController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class WirecardController extends CartController
{
    public function registerPaymentAction(string $type, Context $context, Request $request)
    {
        $wirecardService = $this->get(WirecardService::class);

        $body = $this->getJsonContent($request);
        if (!is_int($body['amount'] ?? null)) {
            throw new PreconditionFailedHttpException('missing amount in JSON body');
        }

        $cart = $this->getCart($context, $request);

        return $wirecardService->registerPayment(new RegisterPaymentCommand([
            'paymentId' => $wirecardService->buildPaymentId($cart),
            'cart' => $cart,
            'type' => $type,
            'amount' => $body['amount'],
        ]));
    }
}
