# HTTP ProductApiDecorator

This class executes REST calls using a configured formatter and configured
endpoint URLs.

## `POST` `https://example.com/beforeGetCategories`

*Before Decorator for getCategories*

Adapt the categories query before the query is executed against the
backend. If nothing is returned the original arguments will be used.
The URL and method can actually be configured by you.

### Request Body

* tuple (array containing):

  * `CategoryQuery`

    * `parentId`: `string`

    * `slug`: `string`

### Responses

Status: 200

Adapt the categories query before the query is executed against the backend.
If nothing is returned the original arguments will be used. The URL and method
can actually be configured by you.

* *optional* tuple (array containing):

  * `CategoryQuery`

    * `parentId`: `string`

    * `slug`: `string`

## `POST` `https://example.com/afterGetCategories`

*After Decorator for getCategories*

Adapt the categories result. If nothing is returned the original result
will be used. The URL and method can actually be configured by you.

### Request Body

* collection of `Category`

  * `categoryId`: `string`

  * `name`: `string`

  * `depth`: `int`

  * `path`: `string`

  * `slug`: `string`

  * `dangerousInnerCategory`: `mixed`

### Responses

Status: 200

Adapt the categories result. If nothing is returned the original result will
be used. The URL and method can actually be configured by you.

* *optional* collection of `Category`

  * `categoryId`: `string`

  * `name`: `string`

  * `depth`: `int`

  * `path`: `string`

  * `slug`: `string`

  * `dangerousInnerCategory`: `mixed`

## `POST` `https://example.com/beforeQuery`

*Before Decorator for query*

Adapt the product query before the query is executed against the
backend. If nothing is returned the original arguments will be used.
The URL and method can actually be configured by you.

### Request Body

* tuple (array containing):

  * `ProductQuery`

    * `category`: *optional* `string`

    * `sku`: *optional* `string`

    * `skus`: either of:

      * collection of `string`

      * `null`

    * `productId`: *optional* `string`

    * `productIds`: either of:

      * collection of `string`

      * `null`

    * `productType`: *optional* `string`

    * `query`: *optional* `string`

    * `filter`: collection of `Filter`

      * `handle`: `string`

      * `attributeType`: *optional* `string`

    * `facets`: collection of `Facet`

      * `handle`: `string`

    * `sortAttributes`: collection of `string`

    * `fuzzy`: `bool`

### Responses

Status: 200

Adapt the product query before the query is executed against the backend. If
nothing is returned the original arguments will be used. The URL and method
can actually be configured by you.

* *optional* tuple (array containing):

  * `ProductQuery`

    * `category`: *optional* `string`

    * `sku`: *optional* `string`

    * `skus`: either of:

      * collection of `string`

      * `null`

    * `productId`: *optional* `string`

    * `productIds`: either of:

      * collection of `string`

      * `null`

    * `productType`: *optional* `string`

    * `query`: *optional* `string`

    * `filter`: collection of `Filter`

      * `handle`: `string`

      * `attributeType`: *optional* `string`

    * `facets`: collection of `Facet`

      * `handle`: `string`

    * `sortAttributes`: collection of `string`

    * `fuzzy`: `bool`

## `POST` `https://example.com/afterQuery`

*After Decorator for query*

Adapt the product query result. If nothing is returned the original
result will be used. The URL and method can actually be configured by
you.

### Request Body

* *optional* `Result`

  * `items` as collection of `Product`

    * `productId`: `string`

    * `changed`: *optional* `\DateTimeImmutable`

    * `version`: *optional* `string`

    * `name`: `string`

    * `slug`: `string`

    * `description`: `string`

    * `categories`: collection of `string`

    * `variants`: collection of `Variant`

      * `id`: `string`

      * `sku`: `string`

      * `groupId`: `string`

      * `price`: `int`

      * `discountedPrice`: *optional* `int`

      * `discounts`: `mixed`

      * `currency`: `string`

      * `attributes`: `array`

      * `images`: `array`

      * `isOnStock`: `bool`

      * `dangerousInnerVariant`: `mixed`

    * `dangerousInnerProduct`: `mixed`

  * `total` as `int`

  * `count` as `int`

  * `items` as `array`

  * `facets` as collection of `Facet`

    * `type`: `string`

    * `handle`: `string`

    * `key`: `string`

    * `selected`: `bool`

  * `query` as `Query`

    * `locale`: `string`

    * `loadDangerousInnerData`: `bool`

### Responses

Status: 200

Adapt the product query result. If nothing is returned the original result
will be used. The URL and method can actually be configured by you.

* *optional* `Result`

  * `items` as collection of `Product`

    * `productId`: `string`

    * `changed`: *optional* `\DateTimeImmutable`

    * `version`: *optional* `string`

    * `name`: `string`

    * `slug`: `string`

    * `description`: `string`

    * `categories`: collection of `string`

    * `variants`: collection of `Variant`

      * `id`: `string`

      * `sku`: `string`

      * `groupId`: `string`

      * `price`: `int`

      * `discountedPrice`: *optional* `int`

      * `discounts`: `mixed`

      * `currency`: `string`

      * `attributes`: `array`

      * `images`: `array`

      * `isOnStock`: `bool`

      * `dangerousInnerVariant`: `mixed`

    * `dangerousInnerProduct`: `mixed`

  * `total` as `int`

  * `count` as `int`

  * `items` as `array`

  * `facets` as collection of `Facet`

    * `type`: `string`

    * `handle`: `string`

    * `key`: `string`

    * `selected`: `bool`

  * `query` as `Query`

    * `locale`: `string`

    * `loadDangerousInnerData`: `bool`

