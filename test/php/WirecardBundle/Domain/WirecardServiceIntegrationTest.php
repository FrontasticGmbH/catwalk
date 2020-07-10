<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Frontastic\Catwalk\IntegrationTest;
use Frontastic\Common\CartApiBundle\Domain\Cart;

class WirecardServiceIntegrationTest extends IntegrationTest
{
    /** @var WirecardService */
    private $wirecardService;

    /**
     * @before
     */
    public function setupService()
    {
        $this->wirecardService = $this->getContainer()->get(WirecardService::class);
    }

    public function testPaymentIdIsUnique()
    {
        $cart = new Cart([
            'cartId' => 'myCartId',
        ]);

        $paymentId1 = $this->wirecardService->buildPaymentId($cart);
        $paymentId2 = $this->wirecardService->buildPaymentId($cart);

        $this->assertNotEquals($paymentId1, $paymentId2);
    }

    public function testRegisterPaymentWithWrongAmountThrowsException()
    {
        $command = new RegisterPaymentCommand([
            'paymentId' => uniqid('', true),
            'amount' => 100,
            'cart' => new Cart([
                'cartId' => '456',
                'sum' => 90,
            ]),
            'type' => RegisterPaymentCommand::TYPE_CREDITCARD,
        ]);

        $this->expectException(\DomainException::class);
        $this->wirecardService->registerPayment($command);
    }

    public function testRegisterPaymentWithUnknownTypeThrowsException()
    {
        $command = new RegisterPaymentCommand([
            'paymentId' => uniqid('', true),
            'amount' => 100,
            'cart' => new Cart([
                'cartId' => '456',
                'sum' => 100,
            ]),
            'type' => 'unknown_payment_type',
        ]);

        $this->expectException(\DomainException::class);
        $this->wirecardService->registerPayment($command);
    }

    public function testRegisterCreditcardPayment()
    {
        $command = new RegisterPaymentCommand([
            'paymentId' => uniqid('', true),
            'amount' => 100,
            'cart' => new Cart([
                'cartId' => '456',
                'sum' => 100,
            ]),
            'type' => RegisterPaymentCommand::TYPE_CREDITCARD,
        ]);

        $result = $this->wirecardService->registerPayment($command);

        $this->assertSame(RegisterPaymentResult::PAYMENT_MODE_SEAMLESS, $result->paymentMode);
        $this->assertStringStartsWith('https://', $result->paymentUrl);
    }
}
