# 

## `POST` `/api/account/address/new`

*Add a new address to account*

### Request Body

* `Address`

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

### Responses

Status: 200

* `\Frontastic\Catwalk\FrontendBundle\Controller\Account`

## `POST` `/api/account/address/update`

*Update address information*

Requires the addressId to be specified in the address to update

### Request Body

* `Address`

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

### Responses

Status: 200

Requires the addressId to be specified in the address to update

* `\Frontastic\Catwalk\FrontendBundle\Controller\Account`

## `POST` `/api/account/address/remove`

*Remove address information*

Requires (only) the addressId to be specified in the address to remove

### Request Body

* `Address`

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

### Responses

Status: 200

Requires (only) the addressId to be specified in the address to remove

* `\Frontastic\Catwalk\FrontendBundle\Controller\Account`

## `POST` `/api/account/address/setDefaultBilling`

*Set an address as default billing address*

Requires (only) the addressId to be specified in the address

### Request Body

* `Address`

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

### Responses

Status: 200

Requires (only) the addressId to be specified in the address

* `\Frontastic\Catwalk\FrontendBundle\Controller\Account`

## `POST` `/api/account/address/setDefaultShipping`

*Set an address as default shipping address*

Requires (only) the addressId to be specified in the address

### Request Body

* `Address`

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

### Responses

Status: 200

Requires (only) the addressId to be specified in the address

* `\Frontastic\Catwalk\FrontendBundle\Controller\Account`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
