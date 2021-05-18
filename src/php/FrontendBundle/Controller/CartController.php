<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Frontastic\Common\AccountApiBundle\Domain\Address;
use Frontastic\Common\CartApiBundle\Domain\Cart;
use Frontastic\Common\CartApiBundle\Domain\CartApi;
use Frontastic\Common\CartApiBundle\Domain\CartApiFactory;
use Frontastic\Common\CartApiBundle\Domain\LineItem;
use Frontastic\Common\CoreBundle\Controller\CrudController;
use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Frontastic\Common\ProductApiBundle\Domain\Variant;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @IgnoreAnnotation("Docs\Request")
 * @IgnoreAnnotation("Docs\Response")
 */
class CartController extends CrudController
{
    /**
     * @var CartApi
     */
    protected $cartApi;

    /**
     * @var CartFetcher
     */
    private $cartFetcher;
    private TrackingService $trackingService;
    private CartApi $cartApiService;
    private LoggerInterface $logger;
    private CartApiFactory $cartApiFactory;

    public function __construct(
        TrackingService $trackingService,
        CartApi $cartApiService,
        CartFetcher $cartFetcher,
        LoggerInterface $logger,
        CartApiFactory $cartApiFactory
    ) {
        $this->trackingService = $trackingService;
        $this->cartApi = $cartApiService;
        $this->cartFetcher = $cartFetcher;
        $this->logger = $logger;
        $this->cartApiFactory = $cartApiFactory;
    }

    /**
     * Get the current cart
     *
     * @Docs\Request(
     *  "GET",
     *  "/api/cart/cart"
     * )
     * @Docs\Response(
     *  "200",
     *  "object{cart: Cart, availableShippingMethods: ShippingMethod[]}"
     * )
     */
    public function getAction(Context $context, Request $request): array
    {
        $cart = $this->getCart($context, $request);
        return [
            'cart' => $cart,
            'availableShippingMethods' => $this->getCartApi($context)->getAvailableShippingMethods(
                $cart,
                $context->locale
            ),
        ];
    }

    /**
     * Get order by order ID
     *
     * The returned order is an extension of the cart, therefore all cart
     * properties also exist.
     *
     * @Docs\Request(
     *  "GET",
     *  "/api/cart/order/{order}
     * )
     * @Docs\Response(
     *  "200",
     *  "object{order: Order}"
     * )
     */
    public function getOrderAction(Context $context, Request $request, string $order): array
    {
        $cartApi = $this->getCartApi($context);
        return [
            'order' => $cartApi->getOrder(
                $context->session->account,
                $order,
                $context->locale
            ),
        ];
    }

    /**
     * Adds a single line item to th cart
     *
     * The line item has to be identified wither by its variant ID or its SKU.
     * Optionally you can pass additional attributes with the line item.
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/cart/cart/add",
     *  "object{variant: object{id: ?string, sku: ?string, attributes: ?mixed}, count: ?int}"
     * )
     * @Docs\Response(
     *  "200",
     *  "object{cart: Cart, addedItems: string[], availableShippingMethods: ShippingMethod[]}"
     * )
     */
    public function addAction(Context $context, Request $request): array
    {
        $payload = $this->getJsonContent($request);
        $cartApi = $this->getCartApi($context);

        $cart = $this->getCart($context, $request);
        $beforeItemIds = $this->getLineItemIds($cart);

        $lineItemVariant = LineItem\Variant::newWithProjectSpecificData(
            array_merge(
                $payload,
                [
                    'variant' => new Variant([
                        'id' => $payload['variant']['id'] ?? null,
                        'sku' => $payload['variant']['sku'] ?? null,
                        'attributes' => $payload['variant']['attributes'] ?? [],
                    ]),
                    'count' => $payload['count'] ?? 1,
                ]
            )
        );
        $lineItemVariant->projectSpecificData = $this->parseProjectSpecificDataByKey($payload, 'option');

        $cartApi->startTransaction($cart);
        $cartApi->addToCart($cart, $lineItemVariant, $context->locale);
        $cart = $cartApi->commit($context->locale);

        $this->get(TrackingService::class)->reachAddToBasket($context, $cart, $lineItemVariant);

        return [
            'cart' => $cart,
            'addedItems' => $this->getLineItems(
                $cart,
                array_diff(
                    $this->getLineItemIds($cart),
                    $beforeItemIds
                )
            ),
            'availableShippingMethods' => $cartApi->getAvailableShippingMethods(
                $cart,
                $context->locale
            ),
        ];
    }

    /**
     * Add multiple line items at once
     *
     * Each line item has to be identified wither by its variant ID or its SKU.
     * Optionally you can pass additional attributes with each line item.
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/cart/cart/addMultiple",
     *  "object{lineItems: object{variant: object{id: ?string, sku: ?string, attributes: ?mixed}, count: ?int}[]}"
     * )
     * @Docs\Response(
     *  "200",
     *  "object{cart: Cart, addedItems: string[], availableShippingMethods: ShippingMethod[]}"
     * )
     */
    public function addMultipleAction(Context $context, Request $request): array
    {
        $payload = $this->getJsonContent($request);

        if (!isset($payload['lineItems']) || !is_array($payload['lineItems'])) {
            throw new BadRequestHttpException('Parameter "lineItems" in payload is not an array.');
        }

        $cartApi = $this->getCartApi($context);

        $cart = $this->getCart($context, $request);
        $beforeItemIds = $this->getLineItemIds($cart);

        $cartApi->startTransaction($cart);
        foreach (($payload['lineItems'] ?? []) as $lineItemData) {
            $lineItemVariant = LineItem\Variant::newWithProjectSpecificData(
                array_merge(
                    $lineItemData,
                    [
                        'variant' => new Variant([
                            'id' => $lineItemData['variant']['id'] ?? null,
                            'sku' => $lineItemData['variant']['sku'] ?? null,
                            'attributes' => $lineItemData['variant']['attributes'] ?? [],
                        ]),
                        'count' => $lineItemData['count'] ?? 1,
                    ]
                )
            );
            // BC
            $lineItemVariant->projectSpecificData = $this->parseProjectSpecificDataByKey($lineItemData, 'option');

            $this->trackingService->reachAddToBasket($context, $cart, $lineItemVariant);
            $cartApi->addToCart($cart, $lineItemVariant, $context->locale);
        }
        $cart = $cartApi->commit($context->locale);

        return [
            'cart' => $cart,
            'addedItems' => $this->getLineItems(
                $cart,
                array_diff(
                    $this->getLineItemIds($cart),
                    $beforeItemIds
                )
            ),
            'availableShippingMethods' => $cartApi->getAvailableShippingMethods(
                $cart,
                $context->locale
            ),
        ];
    }

    /**
     * Change count for line item in cart
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/cart/cart/lineItem",
     *  "object{lineItemId: string, count: int}"
     * )
     * @Docs\Response(
     *  "200",
     *  "object{cart: Cart, availableShippingMethods: ShippingMethod[]}"
     * )
     */
    public function updateLineItemAction(Context $context, Request $request): array
    {
        $payload = $this->getJsonContent($request);
        $cartApi = $this->getCartApi($context);

        $cart = $this->getCart($context, $request);
        $lineItem = $this->getLineItem($cart, $payload['lineItemId']);
        $lineItem->projectSpecificData = $this->parseProjectSpecificDataByKey($payload, 'custom');

        $cartApi->startTransaction($cart);
        $cartApi->updateLineItem(
            $cart,
            $lineItem,
            $payload['count'],
            null,
            $context->locale
        );
        $cart = $cartApi->commit($context->locale);

        return [
            'cart' => $cart,
            'availableShippingMethods' => $cartApi->getAvailableShippingMethods(
                $cart,
                $context->locale
            ),
        ];
    }


    /**
     * Remove line item from cart
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/cart/cart/lineItem/remove",
     *  "object{lineItemId: string}"
     * )
     * @Docs\Response(
     *  "200",
     *  "object{cart: Cart, availableShippingMethods: ShippingMethod[]}"
     * )
     */
    public function removeLineItemAction(Context $context, Request $request): array
    {
        $payload = $this->getJsonContent($request);
        $cartApi = $this->getCartApi($context);

        $cart = $this->getCart($context, $request);

        $cartApi->startTransaction($cart);
        $cartApi->removeLineItem(
            $cart,
            $item = $this->getLineItem($cart, $payload['lineItemId']),
            $context->locale
        );
        $cart = $cartApi->commit($context->locale);

        return [
            'cart' => $cart,
            'removedItems' => [$item],
            'availableShippingMethods' => $cartApi->getAvailableShippingMethods(
                $cart,
                $context->locale
            ),
        ];
    }

    private function getLineItem(Cart $cart, string $lineItemId): LineItem
    {
        foreach ($cart->lineItems as $lineItem) {
            if ($lineItem->lineItemId === $lineItemId) {
                return $lineItem;
            }
        }

        throw new \OutOfBoundsException("Could not find line item with ID $lineItemId");
    }

    private function getLineItems(Cart $cart, array $lineItemIds): array
    {
        $items = [];
        foreach ($cart->lineItems as $lineItem) {
            if (in_array($lineItem->lineItemId, $lineItemIds)) {
                $items[] = $lineItem;
            }
        }
        return $items;
    }

    private function getLineItemIds(Cart $cart): array
    {
        return array_map(
            function (LineItem $lineItem) {
                return $lineItem->lineItemId;
            },
            $cart->lineItems
        );
    }

    /**
     * Update cart properties
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/cart/cart/update",
     *  "object{
     *      account: ?object{email: string},
     *      shipping: ?Address,
     *      billing: ?Address,
     *      shippingMethodName: ?string, custom:?mixed
     *  }"
     * )
     * @Docs\Response(
     *  "200",
     *  "object{cart: Cart, availableShippingMethods: ShippingMethod[]}"
     * )
     */
    public function updateAction(Context $context, Request $request): array
    {
        $payload = $this->getJsonContent($request);
        $cartApi = $this->getCartApi($context);

        $cart = $this->getCart($context, $request);
        $cartApi->startTransaction($cart);

        if (!empty($payload['account'])) {
            $cart = $cartApi->setEmail(
                $cart,
                $payload['account']['email'],
                $context->locale
            );
        }

        if (!empty($payload['shipping']) || !empty($payload['billing'])) {
            $cart = $cartApi->setShippingAddress(
                $cart,
                Address::newWithProjectSpecificData(($payload['shipping'] ?? []) ?: $payload['billing']),
                $context->locale
            );

            $cart = $cartApi->setBillingAddress(
                $cart,
                Address::newWithProjectSpecificData(($payload['billing'] ?? []) ?: $payload['shipping']),
                $context->locale
            );
        }

        if (array_key_exists('shippingMethodName', $payload)) {
            $cart = $cartApi->setShippingMethod(
                $cart,
                $payload['shippingMethodName'] ?? '',
                $context->locale
            );
        }

        $cart->projectSpecificData = $this->parseProjectSpecificDataByKey($payload, 'custom');
        $cart = $cartApi->setRawApiInput($cart, $context->locale);
        $cart = $cartApi->commit($context->locale);

        return [
            'cart' => $cart,
            'availableShippingMethods' => $cartApi->getAvailableShippingMethods(
                $cart,
                $context->locale
            ),
        ];
    }

    /**
     * Convert a (complete) cart into an order
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/cart/cart/checkout"
     * )
     * @Docs\Response(
     *  "200",
     *  "object{order: Order}"
     * )
     */
    public function checkoutAction(Context $context, Request $request): array
    {
        $cartApi = $this->getCartApi($context);
        $cart = $this->getCart($context, $request);

        $order = $cartApi->order($cart, $context->locale);
        $this->get(TrackingService::class)->reachOrder($context, $order);

        $symfonySession = $request->hasSession() ? $request->getSession() : null;
        if ($symfonySession !== null) {
            // Increase security
            session_regenerate_id();
            $symfonySession->remove('cart_id');
        }

        return [
            'order' => $order,
        ];
    }

    /**
     * Redeem a discount code
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/cart/cart/discount/{code}"
     * )
     * @Docs\Response(
     *  "200",
     *  "object{cart: Cart, availableShippingMethods: ShippingMethod[]}"
     * )
     */
    public function redeemDiscountAction(Context $context, Request $request, string $code): array
    {
        $cartApi = $this->getCartApi($context);
        $cart = $cartApi->redeemDiscountCode($this->getCart($context, $request), $code, $context->locale);
        return [
            'cart' => $cart,
            'availableShippingMethods' => $cartApi->getAvailableShippingMethods(
                $cart,
                $context->locale
            ),
        ];
    }

    /**
     * Remove a discount code
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/cart/cart/discount-remove",
     *  "object{discountId: string}"
     * )
     * @Docs\Response(
     *  "200",
     *  "object{cart: Cart, availableShippingMethods: ShippingMethod[]}"
     * )
     */
    public function removeDiscountAction(Context $context, Request $request): array
    {
        $cartApi = $this->getCartApi($context);
        $payload = $this->getJsonContent($request);
        $cart = $cartApi->removeDiscountCode(
            $this->getCart($context, $request),
            new LineItem(['lineItemId' => $payload['discountId']])
        );
        return [
            'cart' => $cart,
            'availableShippingMethods' => $cartApi->getAvailableShippingMethods(
                $cart,
                $context->locale
            ),
        ];
    }

    /**
     * Retrieve a list of all shipping methods
     *
     * @Docs\Request(
     *  "GET",
     *  "/api/cart/shipping-methods"
     * )
     * @Docs\Response(
     *  "200",
     *  "object{shippingMethods: ShippingMethod[]}"
     * )
     */
    public function getShippingMethodsAction(Context $context, Request $request): array
    {
        return [
            'shippingMethods' => $this->getCartApi($context)->getShippingMethods(
                $context->locale,
                $request->query->getBoolean('onlyMatching')
            ),
        ];
    }

    protected function getCartApi(Context $context): CartApi
    {
        if ($this->cartApi) {
            return $this->cartApi;
        }

        /** @var \Frontastic\Common\CartApiBundle\Domain\CartApiFactory $cartApiFactory */
        $cartApiFactory = $this->get('Frontastic\Common\CartApiBundle\Domain\CartApiFactory');
        return $this->cartApi = $cartApiFactory->factor($context->project);
    }

    protected function getCart(Context $context, Request $request): Cart
    {
        return $this->getCartFetcher($context)->fetchCart($context, $request);
    }

    private function getCartFetcher(Context $context): CartFetcher
    {
        if (!isset($this->cartFetcher)) {
            $this->cartFetcher = new CartFetcher($this->getCartApi($context), $this->get('logger'));
        }
        return $this->cartFetcher;
    }

    /**
     * @param Request $request
     *
     * @return array|mixed
     */
    protected function getJsonContent(Request $request)
    {
        if (!$request->getContent() ||
            !($body = Json::decode($request->getContent(), true))) {
            throw new \InvalidArgumentException("Invalid data passed: " . $request->getContent());
        }

        return $body;
    }

    protected function parseProjectSpecificDataByKey(array $requestBody, string $key): array
    {
        $projectSpecificData = $requestBody['projectSpecificData'] ?? [];

        if (!key_exists($key, $projectSpecificData) && key_exists($key, $requestBody)) {
            $this->logger
                ->warning(
                    'This usage of the key "{key}" is deprecated, move it into "projectSpecificData" instead',
                    ['key' => $key]
                );
            $projectSpecificData['custom'] = $requestBody[$key] ?? [];
        }

        return $projectSpecificData;
    }
}
