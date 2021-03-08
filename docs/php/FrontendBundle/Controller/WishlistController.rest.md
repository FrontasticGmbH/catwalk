# 

## `GET` `//show-demo.frontastic.io/api/cart/wishlist`

*Get wishlist for curent user*

The idea behind this method is:

* If a wishlist has been selected (passed $wishlistId), use that

* If no wishlist is explicitely selected (no $wishlistId) then:

  * If the user does not have a wishlist yet, create a default wishlist

    * This means creating an anonymous wishlist for customers who are
      not logged in

  * Select the first wishlist, if one exists

### Request Body

* null

### Responses

Status: 200

The idea behind this method is:

* If a wishlist has been selected (passed $wishlistId), use that

* If no wishlist is explicitely selected (no $wishlistId) then:

  * If the user does not have a wishlist yet, create a default wishlist

    * This means creating an anonymous wishlist for customers who are      
not logged in

  * Select the first wishlist, if one exists

* `object` with:

  * `wishlist` as `Wishlist`

    * `wishlistId`: `string`

    * `wishlistVersion`: `string`

    * `anonymousId`: `string`

    * `accountId`: `string`

    * `name`: collection of `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `addedAt`: `\DateTimeImmutable`

      * `count`: `int`

      * `dangerousInnerItem`: `mixed`

    * `dangerousInnerWishlist`: `mixed`

## `POST` `//show-demo.frontastic.io/api/cart/wishlist/addMultiple`

*Add single variant to wishlist*

### Request Body

* `object` with:

  * `variant` as `object` with:

    * `sku` as *optional* `string`

  * `count` as *optional* `int`

### Responses

Status: 200

* `object` with:

  * `wishlist` as `Wishlist`

    * `wishlistId`: `string`

    * `wishlistVersion`: `string`

    * `anonymousId`: `string`

    * `accountId`: `string`

    * `name`: collection of `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `addedAt`: `\DateTimeImmutable`

      * `count`: `int`

      * `dangerousInnerItem`: `mixed`

    * `dangerousInnerWishlist`: `mixed`

## `POST` `//show-demo.frontastic.io/api/cart/wishlist/addMultiple`

*Add multiple line items to a wishlist*

### Request Body

* `object` with:

  * `lineItems` as collection of `object` with:

    * `variant` as `object` with:

      * `sku` as *optional* `string`

    * `count` as *optional* `int`

### Responses

Status: 200

* `object` with:

  * `wishlist` as `Wishlist`

    * `wishlistId`: `string`

    * `wishlistVersion`: `string`

    * `anonymousId`: `string`

    * `accountId`: `string`

    * `name`: collection of `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `addedAt`: `\DateTimeImmutable`

      * `count`: `int`

      * `dangerousInnerItem`: `mixed`

    * `dangerousInnerWishlist`: `mixed`

## `POST` `//show-demo.frontastic.io/api/cart/wishlist/create`

*Create new wishlist*

### Request Body

* `object` with:

  * `name` as `string`

### Responses

Status: 200

* `Wishlist`

  * `wishlistId`: `string`

  * `wishlistVersion`: `string`

  * `anonymousId`: `string`

  * `accountId`: `string`

  * `name`: collection of `string`

  * `lineItems`: collection of `LineItem`

    * `lineItemId`: `string`

    * `name`: `string`

    * `type`: `string`

    * `addedAt`: `\DateTimeImmutable`

    * `count`: `int`

    * `dangerousInnerItem`: `mixed`

  * `dangerousInnerWishlist`: `mixed`

## `POST` `//show-demo.frontastic.io/api/cart/wishlist/lineItem`

*Remove item from wishlist*

### Request Body

* `object` with:

  * `lineItemId` as `string`

  * `count` as `int`

### Responses

Status: 200

* `object` with:

  * `wishlist` as `Wishlist`

    * `wishlistId`: `string`

    * `wishlistVersion`: `string`

    * `anonymousId`: `string`

    * `accountId`: `string`

    * `name`: collection of `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `addedAt`: `\DateTimeImmutable`

      * `count`: `int`

      * `dangerousInnerItem`: `mixed`

    * `dangerousInnerWishlist`: `mixed`

## `POST` `//show-demo.frontastic.io/api/cart/wishlist/lineItem/remove`

*Remove item from wishlist*

### Request Body

* `object` with:

  * `lineItemId` as `string`

### Responses

Status: 200

* `object` with:

  * `wishlist` as `Wishlist`

    * `wishlistId`: `string`

    * `wishlistVersion`: `string`

    * `anonymousId`: `string`

    * `accountId`: `string`

    * `name`: collection of `string`

    * `lineItems`: collection of `LineItem`

      * `lineItemId`: `string`

      * `name`: `string`

      * `type`: `string`

      * `addedAt`: `\DateTimeImmutable`

      * `count`: `int`

      * `dangerousInnerItem`: `mixed`

    * `dangerousInnerWishlist`: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
