<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Frontastic\Common\CartApiBundle\Domain\Cart;

class WirecardService
{
    /** @var WirecardClient */
    private $wirecardClient;

    /** @var array<string, WirecardCredentials> */
    private $credentials;

    public function __construct(WirecardClient $wirecardClient, array $credentials)
    {
        $this->credentials = $credentials;
        $this->wirecardClient = $wirecardClient;
    }

    public function buildPaymentId(Cart $cart): string
    {
        return sprintf('%s-%s-%s', $cart->cartId, $cart->cartVersion, uniqid());
    }

    public function registerPayment(RegisterPaymentCommand $command): RegisterPaymentResult
    {
        if ($command->amount !== $command->cart->sum) {
            throw new \DomainException('Cart sum does not equal payment amount');
        }

        if (!array_key_exists($command->getCredentialsType(), $this->credentials)) {
            throw new \DomainException('missing credentials of type ' . $command->getCredentialsType());
        }
        $credentials = $this->credentials[$command->getCredentialsType()];

        $requestBody = [
            'payment' => [
                'merchant-account-id' => [
                    'value' => $credentials->merchant,
                ],
                'locale' => 'de',
                'request-id' => $command->paymentId,
                'transaction-type' => 'purchase',
                'requested-amount' => [
                    'value' => ($command->amount / 100),
                    'currency' => 'EUR',
                ],
                'payment-methods' => [
                    'payment-method' => [
                        [
                            'name' => $command->type,
                        ],
                    ],
                ],
                'success-redirect-url' => 'https://frontastic.io/wirecard/confirm/success',
                'fail-redirect-url' => 'https://frontastic.io/wirecard/confirm/fail',
                'cancel-redirect-url' => 'https://frontastic.io/wirecard/confirm/cancel',
            ],
            'options' => [
                'mode' => RegisterPaymentResult::PAYMENT_MODE_SEAMLESS,
                'frame-ancestor' => 'https://frontastic.io',
            ],
        ];

        switch ($command->type) {
            case RegisterPaymentCommand::TYPE_CREDITCARD:
                break;
            default:
                throw new \DomainException('unknown payment type: ' . $command->type);
        }

        $result = $this->wirecardClient->post('/api/payment/register', $requestBody, $credentials);

        return new RegisterPaymentResult([
            'paymentUrl' => $result['payment-redirect-url'],
            'paymentMode' => RegisterPaymentResult::PAYMENT_MODE_SEAMLESS,
        ]);
    }
}
