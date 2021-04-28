# 

## `GET` `/api/cart/cart`

*Get the current cart*

### Request Body

* null

### Responses

Status: 200

* `object` with:

  * `cart` as `Cart`

    * `cartId`: `string`

    * `cartVersion`: `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `count`: `int`

      * `price`: `int`

      * `discountedPrice`: *optional* `int`

      * `discountTexts`: `array`

      * `discounts`: collection of `Discount`

        * `discountId`: `string`

        * `code`: `string`

        * `state`: `string`

        * `name`: `\Frontastic\Common\Translatable`

        * `description`: `\Frontastic\Common\Translatable`

        * `discountedAmount`: *optional* `int`

        * `dangerousInnerDiscount`: `mixed`

      * `totalPrice`: `int`

      * `currency`: `string`

      * `isGift`: `bool`

      * `dangerousInnerItem`: `mixed`

    * `email`: `string`

    * `birthday`: `\DateTimeImmutable`

    * `shippingInfo`: *optional* `ShippingInfo`

      * `price`: `int`

      * `dangerousInnerShippingInfo`: *optional* `mixed`

    * `shippingMethod`: *optional* `ShippingMethod`

      * `shippingMethodId`: `string`

      * `name`: `string`

      * `price`: `int`

      * `description`: `string`

      * `rates`: *optional* collection of `ShippingRate`

        * `zoneId`: `string`

        * `name`: `string`

        * `locations`: *optional* collection of `ShippingLocation`

          * `country`: `string`

          * `state`: *optional* `string`

          * `dangerousInnerShippingLocation`: *optional* `mixed`

        * `currency`: `string`

        * `price`: `int`

        * `dangerousInnerShippingRate`: *optional* `mixed`

      * `dangerousInnerShippingMethod`: *optional* `mixed`

    * `shippingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `billingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `sum`: `int`

    * `currency`: `string`

    * `payments`: collection of `Payment`

      * `id`: `string`

      * `paymentProvider`: `string`

      * `paymentId`: `string`

      * `amount`: `int`

      * `currency`: `string`

      * `debug`: `string`

      * `paymentStatus`: `string`

      * `version`: `int`

      * `paymentMethod`: `string`

      * `paymentDetails`: either of:

        * `array`

        * `null`

    * `discountCodes`: collection of `Discount`

      * `discountId`: `string`

      * `code`: `string`

      * `state`: `string`

      * `name`: `\Frontastic\Common\Translatable`

      * `description`: `\Frontastic\Common\Translatable`

      * `discountedAmount`: *optional* `int`

      * `dangerousInnerDiscount`: `mixed`

    * `taxed`: *optional* `Tax`

      * `amount`: `int`

      * `currency`: `string`

      * `taxPortions`: collection of `TaxPortion`

        * `amount`: `int`

        * `currency`: `string`

        * `name`: `string`

        * `rate`: `float`

    * `dangerousInnerCart`: `mixed`

  * `availableShippingMethods` as collection of `\Frontastic\Catwalk\FrontendBundle\Controller\ShippingMethod`

## `GET` `/api/cart/order/{order}`

*Get order by order ID*

The returned order is an extension of the cart, therefore all cart
properties also exist.

### Request Body

* null

### Responses

Status: 200

The returned order is an extension of the cart, therefore all cart properties
also exist.

* `object` with:

  * `order` as `\Frontastic\Catwalk\FrontendBundle\Controller\Order`

## `POST` `/api/cart/cart/add`

*Adds a single line item to th cart*

The line item has to be identified wither by its variant ID or its SKU.
Optionally you can pass additional attributes with the line item.

### Request Body

* `object` with:

  * `variant` as `object` with:

    * `id` as *optional* `string`

    * `sku` as *optional* `string`

    * `attributes` as *optional* `mixed`

  * `count` as *optional* `int`

### Responses

Status: 200

The line item has to be identified wither by its variant ID or its SKU.
Optionally you can pass additional attributes with the line item.

* `object` with:

  * `cart` as `Cart`

    * `cartId`: `string`

    * `cartVersion`: `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `count`: `int`

      * `price`: `int`

      * `discountedPrice`: *optional* `int`

      * `discountTexts`: `array`

      * `discounts`: collection of `Discount`

        * `discountId`: `string`

        * `code`: `string`

        * `state`: `string`

        * `name`: `\Frontastic\Common\Translatable`

        * `description`: `\Frontastic\Common\Translatable`

        * `discountedAmount`: *optional* `int`

        * `dangerousInnerDiscount`: `mixed`

      * `totalPrice`: `int`

      * `currency`: `string`

      * `isGift`: `bool`

      * `dangerousInnerItem`: `mixed`

    * `email`: `string`

    * `birthday`: `\DateTimeImmutable`

    * `shippingInfo`: *optional* `ShippingInfo`

      * `price`: `int`

      * `dangerousInnerShippingInfo`: *optional* `mixed`

    * `shippingMethod`: *optional* `ShippingMethod`

      * `shippingMethodId`: `string`

      * `name`: `string`

      * `price`: `int`

      * `description`: `string`

      * `rates`: *optional* collection of `ShippingRate`

        * `zoneId`: `string`

        * `name`: `string`

        * `locations`: *optional* collection of `ShippingLocation`

          * `country`: `string`

          * `state`: *optional* `string`

          * `dangerousInnerShippingLocation`: *optional* `mixed`

        * `currency`: `string`

        * `price`: `int`

        * `dangerousInnerShippingRate`: *optional* `mixed`

      * `dangerousInnerShippingMethod`: *optional* `mixed`

    * `shippingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `billingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `sum`: `int`

    * `currency`: `string`

    * `payments`: collection of `Payment`

      * `id`: `string`

      * `paymentProvider`: `string`

      * `paymentId`: `string`

      * `amount`: `int`

      * `currency`: `string`

      * `debug`: `string`

      * `paymentStatus`: `string`

      * `version`: `int`

      * `paymentMethod`: `string`

      * `paymentDetails`: either of:

        * `array`

        * `null`

    * `discountCodes`: collection of `Discount`

      * `discountId`: `string`

      * `code`: `string`

      * `state`: `string`

      * `name`: `\Frontastic\Common\Translatable`

      * `description`: `\Frontastic\Common\Translatable`

      * `discountedAmount`: *optional* `int`

      * `dangerousInnerDiscount`: `mixed`

    * `taxed`: *optional* `Tax`

      * `amount`: `int`

      * `currency`: `string`

      * `taxPortions`: collection of `TaxPortion`

        * `amount`: `int`

        * `currency`: `string`

        * `name`: `string`

        * `rate`: `float`

    * `dangerousInnerCart`: `mixed`

  * `addedItems` as collection of `string`

  * `availableShippingMethods` as collection of `\Frontastic\Catwalk\FrontendBundle\Controller\ShippingMethod`

## `POST` `/api/cart/cart/addMultiple`

*Add multiple line items at once*

Each line item has to be identified wither by its variant ID or its SKU.
Optionally you can pass additional attributes with each line item.

### Request Body

* `object` with:

  * `lineItems` as collection of `object` with:

    * `variant` as `object` with:

      * `id` as *optional* `string`

      * `sku` as *optional* `string`

      * `attributes` as *optional* `mixed`

    * `count` as *optional* `int`

### Responses

Status: 200

Each line item has to be identified wither by its variant ID or its SKU.
Optionally you can pass additional attributes with each line item.

* `object` with:

  * `cart` as `Cart`

    * `cartId`: `string`

    * `cartVersion`: `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `count`: `int`

      * `price`: `int`

      * `discountedPrice`: *optional* `int`

      * `discountTexts`: `array`

      * `discounts`: collection of `Discount`

        * `discountId`: `string`

        * `code`: `string`

        * `state`: `string`

        * `name`: `\Frontastic\Common\Translatable`

        * `description`: `\Frontastic\Common\Translatable`

        * `discountedAmount`: *optional* `int`

        * `dangerousInnerDiscount`: `mixed`

      * `totalPrice`: `int`

      * `currency`: `string`

      * `isGift`: `bool`

      * `dangerousInnerItem`: `mixed`

    * `email`: `string`

    * `birthday`: `\DateTimeImmutable`

    * `shippingInfo`: *optional* `ShippingInfo`

      * `price`: `int`

      * `dangerousInnerShippingInfo`: *optional* `mixed`

    * `shippingMethod`: *optional* `ShippingMethod`

      * `shippingMethodId`: `string`

      * `name`: `string`

      * `price`: `int`

      * `description`: `string`

      * `rates`: *optional* collection of `ShippingRate`

        * `zoneId`: `string`

        * `name`: `string`

        * `locations`: *optional* collection of `ShippingLocation`

          * `country`: `string`

          * `state`: *optional* `string`

          * `dangerousInnerShippingLocation`: *optional* `mixed`

        * `currency`: `string`

        * `price`: `int`

        * `dangerousInnerShippingRate`: *optional* `mixed`

      * `dangerousInnerShippingMethod`: *optional* `mixed`

    * `shippingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `billingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `sum`: `int`

    * `currency`: `string`

    * `payments`: collection of `Payment`

      * `id`: `string`

      * `paymentProvider`: `string`

      * `paymentId`: `string`

      * `amount`: `int`

      * `currency`: `string`

      * `debug`: `string`

      * `paymentStatus`: `string`

      * `version`: `int`

      * `paymentMethod`: `string`

      * `paymentDetails`: either of:

        * `array`

        * `null`

    * `discountCodes`: collection of `Discount`

      * `discountId`: `string`

      * `code`: `string`

      * `state`: `string`

      * `name`: `\Frontastic\Common\Translatable`

      * `description`: `\Frontastic\Common\Translatable`

      * `discountedAmount`: *optional* `int`

      * `dangerousInnerDiscount`: `mixed`

    * `taxed`: *optional* `Tax`

      * `amount`: `int`

      * `currency`: `string`

      * `taxPortions`: collection of `TaxPortion`

        * `amount`: `int`

        * `currency`: `string`

        * `name`: `string`

        * `rate`: `float`

    * `dangerousInnerCart`: `mixed`

  * `addedItems` as collection of `string`

  * `availableShippingMethods` as collection of `\Frontastic\Catwalk\FrontendBundle\Controller\ShippingMethod`

## `POST` `/api/cart/cart/lineItem`

*Change count for line item in cart*

### Request Body

* `object` with:

  * `lineItemId` as `string`

  * `count` as `int`

### Responses

Status: 200

* `object` with:

  * `cart` as `Cart`

    * `cartId`: `string`

    * `cartVersion`: `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `count`: `int`

      * `price`: `int`

      * `discountedPrice`: *optional* `int`

      * `discountTexts`: `array`

      * `discounts`: collection of `Discount`

        * `discountId`: `string`

        * `code`: `string`

        * `state`: `string`

        * `name`: `\Frontastic\Common\Translatable`

        * `description`: `\Frontastic\Common\Translatable`

        * `discountedAmount`: *optional* `int`

        * `dangerousInnerDiscount`: `mixed`

      * `totalPrice`: `int`

      * `currency`: `string`

      * `isGift`: `bool`

      * `dangerousInnerItem`: `mixed`

    * `email`: `string`

    * `birthday`: `\DateTimeImmutable`

    * `shippingInfo`: *optional* `ShippingInfo`

      * `price`: `int`

      * `dangerousInnerShippingInfo`: *optional* `mixed`

    * `shippingMethod`: *optional* `ShippingMethod`

      * `shippingMethodId`: `string`

      * `name`: `string`

      * `price`: `int`

      * `description`: `string`

      * `rates`: *optional* collection of `ShippingRate`

        * `zoneId`: `string`

        * `name`: `string`

        * `locations`: *optional* collection of `ShippingLocation`

          * `country`: `string`

          * `state`: *optional* `string`

          * `dangerousInnerShippingLocation`: *optional* `mixed`

        * `currency`: `string`

        * `price`: `int`

        * `dangerousInnerShippingRate`: *optional* `mixed`

      * `dangerousInnerShippingMethod`: *optional* `mixed`

    * `shippingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `billingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `sum`: `int`

    * `currency`: `string`

    * `payments`: collection of `Payment`

      * `id`: `string`

      * `paymentProvider`: `string`

      * `paymentId`: `string`

      * `amount`: `int`

      * `currency`: `string`

      * `debug`: `string`

      * `paymentStatus`: `string`

      * `version`: `int`

      * `paymentMethod`: `string`

      * `paymentDetails`: either of:

        * `array`

        * `null`

    * `discountCodes`: collection of `Discount`

      * `discountId`: `string`

      * `code`: `string`

      * `state`: `string`

      * `name`: `\Frontastic\Common\Translatable`

      * `description`: `\Frontastic\Common\Translatable`

      * `discountedAmount`: *optional* `int`

      * `dangerousInnerDiscount`: `mixed`

    * `taxed`: *optional* `Tax`

      * `amount`: `int`

      * `currency`: `string`

      * `taxPortions`: collection of `TaxPortion`

        * `amount`: `int`

        * `currency`: `string`

        * `name`: `string`

        * `rate`: `float`

    * `dangerousInnerCart`: `mixed`

  * `availableShippingMethods` as collection of `\Frontastic\Catwalk\FrontendBundle\Controller\ShippingMethod`

## `POST` `/api/cart/cart/lineItem/remove`

*Remove line item from cart*

### Request Body

* `object` with:

  * `lineItemId` as `string`

### Responses

Status: 200

* `object` with:

  * `cart` as `Cart`

    * `cartId`: `string`

    * `cartVersion`: `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `count`: `int`

      * `price`: `int`

      * `discountedPrice`: *optional* `int`

      * `discountTexts`: `array`

      * `discounts`: collection of `Discount`

        * `discountId`: `string`

        * `code`: `string`

        * `state`: `string`

        * `name`: `\Frontastic\Common\Translatable`

        * `description`: `\Frontastic\Common\Translatable`

        * `discountedAmount`: *optional* `int`

        * `dangerousInnerDiscount`: `mixed`

      * `totalPrice`: `int`

      * `currency`: `string`

      * `isGift`: `bool`

      * `dangerousInnerItem`: `mixed`

    * `email`: `string`

    * `birthday`: `\DateTimeImmutable`

    * `shippingInfo`: *optional* `ShippingInfo`

      * `price`: `int`

      * `dangerousInnerShippingInfo`: *optional* `mixed`

    * `shippingMethod`: *optional* `ShippingMethod`

      * `shippingMethodId`: `string`

      * `name`: `string`

      * `price`: `int`

      * `description`: `string`

      * `rates`: *optional* collection of `ShippingRate`

        * `zoneId`: `string`

        * `name`: `string`

        * `locations`: *optional* collection of `ShippingLocation`

          * `country`: `string`

          * `state`: *optional* `string`

          * `dangerousInnerShippingLocation`: *optional* `mixed`

        * `currency`: `string`

        * `price`: `int`

        * `dangerousInnerShippingRate`: *optional* `mixed`

      * `dangerousInnerShippingMethod`: *optional* `mixed`

    * `shippingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `billingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `sum`: `int`

    * `currency`: `string`

    * `payments`: collection of `Payment`

      * `id`: `string`

      * `paymentProvider`: `string`

      * `paymentId`: `string`

      * `amount`: `int`

      * `currency`: `string`

      * `debug`: `string`

      * `paymentStatus`: `string`

      * `version`: `int`

      * `paymentMethod`: `string`

      * `paymentDetails`: either of:

        * `array`

        * `null`

    * `discountCodes`: collection of `Discount`

      * `discountId`: `string`

      * `code`: `string`

      * `state`: `string`

      * `name`: `\Frontastic\Common\Translatable`

      * `description`: `\Frontastic\Common\Translatable`

      * `discountedAmount`: *optional* `int`

      * `dangerousInnerDiscount`: `mixed`

    * `taxed`: *optional* `Tax`

      * `amount`: `int`

      * `currency`: `string`

      * `taxPortions`: collection of `TaxPortion`

        * `amount`: `int`

        * `currency`: `string`

        * `name`: `string`

        * `rate`: `float`

    * `dangerousInnerCart`: `mixed`

  * `availableShippingMethods` as collection of `\Frontastic\Catwalk\FrontendBundle\Controller\ShippingMethod`

## `POST` `/api/cart/cart/update`

*Update cart properties*

### Request Body

* `object` with:

  * `account` as *optional* `object` with:

    * `email` as `string`

  * `shipping` as *optional* `Address`

    * `addressId`: `string`

    * `salutation`: `string`

    * `firstName`: `string`

    * `lastName`: `string`

    * `streetName`: `string`

    * `streetNumber`: `string`

    * `additionalStreetInfo`: `string`

    * `additionalAddressInfo`: `string`

    * `postalCode`: `string`

    * `city`: `string`

    * `country`: `string`

    * `state`: `string`

    * `phone`: `string`

    * `isDefaultBillingAddress`: `bool`

    * `isDefaultShippingAddress`: `bool`

    * `dangerousInnerAddress`: `mixed`

  * `billing` as *optional* `Address`

    * `addressId`: `string`

    * `salutation`: `string`

    * `firstName`: `string`

    * `lastName`: `string`

    * `streetName`: `string`

    * `streetNumber`: `string`

    * `additionalStreetInfo`: `string`

    * `additionalAddressInfo`: `string`

    * `postalCode`: `string`

    * `city`: `string`

    * `country`: `string`

    * `state`: `string`

    * `phone`: `string`

    * `isDefaultBillingAddress`: `bool`

    * `isDefaultShippingAddress`: `bool`

    * `dangerousInnerAddress`: `mixed`

  * `shippingMethodName` as *optional* `string`

  * `custom` as *optional* `mixed`

### Responses

Status: 200

* `object` with:

  * `cart` as `Cart`

    * `cartId`: `string`

    * `cartVersion`: `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `count`: `int`

      * `price`: `int`

      * `discountedPrice`: *optional* `int`

      * `discountTexts`: `array`

      * `discounts`: collection of `Discount`

        * `discountId`: `string`

        * `code`: `string`

        * `state`: `string`

        * `name`: `\Frontastic\Common\Translatable`

        * `description`: `\Frontastic\Common\Translatable`

        * `discountedAmount`: *optional* `int`

        * `dangerousInnerDiscount`: `mixed`

      * `totalPrice`: `int`

      * `currency`: `string`

      * `isGift`: `bool`

      * `dangerousInnerItem`: `mixed`

    * `email`: `string`

    * `birthday`: `\DateTimeImmutable`

    * `shippingInfo`: *optional* `ShippingInfo`

      * `price`: `int`

      * `dangerousInnerShippingInfo`: *optional* `mixed`

    * `shippingMethod`: *optional* `ShippingMethod`

      * `shippingMethodId`: `string`

      * `name`: `string`

      * `price`: `int`

      * `description`: `string`

      * `rates`: *optional* collection of `ShippingRate`

        * `zoneId`: `string`

        * `name`: `string`

        * `locations`: *optional* collection of `ShippingLocation`

          * `country`: `string`

          * `state`: *optional* `string`

          * `dangerousInnerShippingLocation`: *optional* `mixed`

        * `currency`: `string`

        * `price`: `int`

        * `dangerousInnerShippingRate`: *optional* `mixed`

      * `dangerousInnerShippingMethod`: *optional* `mixed`

    * `shippingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `billingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `sum`: `int`

    * `currency`: `string`

    * `payments`: collection of `Payment`

      * `id`: `string`

      * `paymentProvider`: `string`

      * `paymentId`: `string`

      * `amount`: `int`

      * `currency`: `string`

      * `debug`: `string`

      * `paymentStatus`: `string`

      * `version`: `int`

      * `paymentMethod`: `string`

      * `paymentDetails`: either of:

        * `array`

        * `null`

    * `discountCodes`: collection of `Discount`

      * `discountId`: `string`

      * `code`: `string`

      * `state`: `string`

      * `name`: `\Frontastic\Common\Translatable`

      * `description`: `\Frontastic\Common\Translatable`

      * `discountedAmount`: *optional* `int`

      * `dangerousInnerDiscount`: `mixed`

    * `taxed`: *optional* `Tax`

      * `amount`: `int`

      * `currency`: `string`

      * `taxPortions`: collection of `TaxPortion`

        * `amount`: `int`

        * `currency`: `string`

        * `name`: `string`

        * `rate`: `float`

    * `dangerousInnerCart`: `mixed`

  * `availableShippingMethods` as collection of `\Frontastic\Catwalk\FrontendBundle\Controller\ShippingMethod`

## `POST` `/api/cart/cart/checkout`

*Convert a (complete) cart into an order*

### Request Body

* null

### Responses

Status: 200

* `object` with:

  * `order` as `\Frontastic\Catwalk\FrontendBundle\Controller\Order`

## `POST` `/api/cart/cart/discount/{code}`

*Redeem a discount code*

### Request Body

* null

### Responses

Status: 200

* `object` with:

  * `cart` as `Cart`

    * `cartId`: `string`

    * `cartVersion`: `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `count`: `int`

      * `price`: `int`

      * `discountedPrice`: *optional* `int`

      * `discountTexts`: `array`

      * `discounts`: collection of `Discount`

        * `discountId`: `string`

        * `code`: `string`

        * `state`: `string`

        * `name`: `\Frontastic\Common\Translatable`

        * `description`: `\Frontastic\Common\Translatable`

        * `discountedAmount`: *optional* `int`

        * `dangerousInnerDiscount`: `mixed`

      * `totalPrice`: `int`

      * `currency`: `string`

      * `isGift`: `bool`

      * `dangerousInnerItem`: `mixed`

    * `email`: `string`

    * `birthday`: `\DateTimeImmutable`

    * `shippingInfo`: *optional* `ShippingInfo`

      * `price`: `int`

      * `dangerousInnerShippingInfo`: *optional* `mixed`

    * `shippingMethod`: *optional* `ShippingMethod`

      * `shippingMethodId`: `string`

      * `name`: `string`

      * `price`: `int`

      * `description`: `string`

      * `rates`: *optional* collection of `ShippingRate`

        * `zoneId`: `string`

        * `name`: `string`

        * `locations`: *optional* collection of `ShippingLocation`

          * `country`: `string`

          * `state`: *optional* `string`

          * `dangerousInnerShippingLocation`: *optional* `mixed`

        * `currency`: `string`

        * `price`: `int`

        * `dangerousInnerShippingRate`: *optional* `mixed`

      * `dangerousInnerShippingMethod`: *optional* `mixed`

    * `shippingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `billingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `sum`: `int`

    * `currency`: `string`

    * `payments`: collection of `Payment`

      * `id`: `string`

      * `paymentProvider`: `string`

      * `paymentId`: `string`

      * `amount`: `int`

      * `currency`: `string`

      * `debug`: `string`

      * `paymentStatus`: `string`

      * `version`: `int`

      * `paymentMethod`: `string`

      * `paymentDetails`: either of:

        * `array`

        * `null`

    * `discountCodes`: collection of `Discount`

      * `discountId`: `string`

      * `code`: `string`

      * `state`: `string`

      * `name`: `\Frontastic\Common\Translatable`

      * `description`: `\Frontastic\Common\Translatable`

      * `discountedAmount`: *optional* `int`

      * `dangerousInnerDiscount`: `mixed`

    * `taxed`: *optional* `Tax`

      * `amount`: `int`

      * `currency`: `string`

      * `taxPortions`: collection of `TaxPortion`

        * `amount`: `int`

        * `currency`: `string`

        * `name`: `string`

        * `rate`: `float`

    * `dangerousInnerCart`: `mixed`

  * `availableShippingMethods` as collection of `\Frontastic\Catwalk\FrontendBundle\Controller\ShippingMethod`

## `POST` `/api/cart/cart/discount-remove`

*Remove a discount code*

### Request Body

* `object` with:

  * `discountId` as `string`

### Responses

Status: 200

* `object` with:

  * `cart` as `Cart`

    * `cartId`: `string`

    * `cartVersion`: `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `count`: `int`

      * `price`: `int`

      * `discountedPrice`: *optional* `int`

      * `discountTexts`: `array`

      * `discounts`: collection of `Discount`

        * `discountId`: `string`

        * `code`: `string`

        * `state`: `string`

        * `name`: `\Frontastic\Common\Translatable`

        * `description`: `\Frontastic\Common\Translatable`

        * `discountedAmount`: *optional* `int`

        * `dangerousInnerDiscount`: `mixed`

      * `totalPrice`: `int`

      * `currency`: `string`

      * `isGift`: `bool`

      * `dangerousInnerItem`: `mixed`

    * `email`: `string`

    * `birthday`: `\DateTimeImmutable`

    * `shippingInfo`: *optional* `ShippingInfo`

      * `price`: `int`

      * `dangerousInnerShippingInfo`: *optional* `mixed`

    * `shippingMethod`: *optional* `ShippingMethod`

      * `shippingMethodId`: `string`

      * `name`: `string`

      * `price`: `int`

      * `description`: `string`

      * `rates`: *optional* collection of `ShippingRate`

        * `zoneId`: `string`

        * `name`: `string`

        * `locations`: *optional* collection of `ShippingLocation`

          * `country`: `string`

          * `state`: *optional* `string`

          * `dangerousInnerShippingLocation`: *optional* `mixed`

        * `currency`: `string`

        * `price`: `int`

        * `dangerousInnerShippingRate`: *optional* `mixed`

      * `dangerousInnerShippingMethod`: *optional* `mixed`

    * `shippingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `billingAddress`: *optional* `Address`

      * `addressId`: `string`

      * `salutation`: `string`

      * `firstName`: `string`

      * `lastName`: `string`

      * `streetName`: `string`

      * `streetNumber`: `string`

      * `additionalStreetInfo`: `string`

      * `additionalAddressInfo`: `string`

      * `postalCode`: `string`

      * `city`: `string`

      * `country`: `string`

      * `state`: `string`

      * `phone`: `string`

      * `isDefaultBillingAddress`: `bool`

      * `isDefaultShippingAddress`: `bool`

      * `dangerousInnerAddress`: `mixed`

    * `sum`: `int`

    * `currency`: `string`

    * `payments`: collection of `Payment`

      * `id`: `string`

      * `paymentProvider`: `string`

      * `paymentId`: `string`

      * `amount`: `int`

      * `currency`: `string`

      * `debug`: `string`

      * `paymentStatus`: `string`

      * `version`: `int`

      * `paymentMethod`: `string`

      * `paymentDetails`: either of:

        * `array`

        * `null`

    * `discountCodes`: collection of `Discount`

      * `discountId`: `string`

      * `code`: `string`

      * `state`: `string`

      * `name`: `\Frontastic\Common\Translatable`

      * `description`: `\Frontastic\Common\Translatable`

      * `discountedAmount`: *optional* `int`

      * `dangerousInnerDiscount`: `mixed`

    * `taxed`: *optional* `Tax`

      * `amount`: `int`

      * `currency`: `string`

      * `taxPortions`: collection of `TaxPortion`

        * `amount`: `int`

        * `currency`: `string`

        * `name`: `string`

        * `rate`: `float`

    * `dangerousInnerCart`: `mixed`

  * `availableShippingMethods` as collection of `\Frontastic\Catwalk\FrontendBundle\Controller\ShippingMethod`

## `GET` `/api/cart/shipping-methods`

*Retrieve a list of all shipping methods*

### Request Body

* null

### Responses

Status: 200

* `object` with:

  * `shippingMethods` as collection of `\Frontastic\Catwalk\FrontendBundle\Controller\ShippingMethod`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
