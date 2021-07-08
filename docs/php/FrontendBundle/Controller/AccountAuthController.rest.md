# 

## `POST` `/api/account/register`

*Register a new user*

### Request Body

* `Account`

  * `birthday` as *optional* `string`

  * `phone` as *optional* `string`

  * `phonePrefix` as *optional* `string`

  * `billingAddress` as *optional* `Address`

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

  * `shippingAddress` as *optional* `Address`

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

  * `birthday` as `\DateTime`

  * `groups` as collection of `Group`

    * `groupId`: `string`

    * `name`: `string`

    * `permissions`: collection of `string`

  * `confirmationToken` as `string`

  * `confirmed` as `bool`

  * `tokenValidUntil` as `\DateTime`

  * `addresses` as collection of `Address`

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

  * `authToken` as either of:

    * `string`

    * `null`

  * `apiToken` as either of:

    * `string`

    * `null`

  * `dangerousInnerAccount` as `mixed`

### Responses

Status: 200

* `\Frontastic\Catwalk\FrontendBundle\Controller\Session`

## `POST` `/api/account/confirm/{confirmationToken}`

*Confirm user by their confirmation token (from email)*

### Request Body

* null

### Responses

Status: 200

* `\Frontastic\Catwalk\FrontendBundle\Controller\Session`

## `POST` `/api/account/request`

*Request new reset token*

### Request Body

* `object` with:

  * `email` as `string`

### Responses

Status: 200

* `\Frontastic\Catwalk\FrontendBundle\Controller\Session`

## `POST` `/api/account/reset/{token}`

*Request new reset token*

### Request Body

* `object` with:

  * `newPassword` as `string`

### Responses

Status: 200

* `\Frontastic\Catwalk\FrontendBundle\Controller\Session`

## `POST` `/api/account/password`

*Change password of current account*

### Request Body

* `object` with:

  * `oldPassword` as `string`

  * `newPassword` as `string`

### Responses

Status: 201

* `Account`

  * `accountId`: `string`

  * `email`: `string`

  * `salutation`: `string`

  * `firstName`: `string`

  * `lastName`: `string`

  * `birthday`: `\DateTime`

  * `groups`: collection of `Group`

    * `groupId`: `string`

    * `name`: `string`

    * `permissions`: collection of `string`

  * `confirmationToken`: `string`

  * `confirmed`: `bool`

  * `tokenValidUntil`: `\DateTime`

  * `addresses`: collection of `Address`

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

  * `authToken`: either of:

    * `string`

    * `null`

  * `apiToken`: either of:

    * `string`

    * `null`

  * `dangerousInnerAccount`: `mixed`

## `POST` `/api/account/update`

*Update properties in user acccount*

### Request Body

* `Account`

  * `birthday` as *optional* `string`

  * `email` as `string`

  * `salutation` as `string`

  * `firstName` as `string`

  * `lastName` as `string`

  * `birthday` as `\DateTime`

  * `groups` as collection of `Group`

    * `groupId`: `string`

    * `name`: `string`

    * `permissions`: collection of `string`

  * `confirmationToken` as `string`

  * `confirmed` as `bool`

  * `tokenValidUntil` as `\DateTime`

  * `addresses` as collection of `Address`

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

  * `authToken` as either of:

    * `string`

    * `null`

  * `apiToken` as either of:

    * `string`

    * `null`

  * `dangerousInnerAccount` as `mixed`

### Responses

Status: 200

* `Account`

  * `accountId`: `string`

  * `email`: `string`

  * `salutation`: `string`

  * `firstName`: `string`

  * `lastName`: `string`

  * `birthday`: `\DateTime`

  * `groups`: collection of `Group`

    * `groupId`: `string`

    * `name`: `string`

    * `permissions`: collection of `string`

  * `confirmationToken`: `string`

  * `confirmed`: `bool`

  * `tokenValidUntil`: `\DateTime`

  * `addresses`: collection of `Address`

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

  * `authToken`: either of:

    * `string`

    * `null`

  * `apiToken`: either of:

    * `string`

    * `null`

  * `dangerousInnerAccount`: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
