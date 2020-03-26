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

  * `\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery`

### Responses

Status: 200

Adapt the categories query before the query is executed against the backend.
If nothing is returned the original arguments will be used. The URL and method
can actually be configured by you.

* *optional* tuple (array containing):

    * `\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery`

## `POST` `https://example.com/afterGetCategories`

*After Decorator for getCategories*

Adapt the categories result. If nothing is returned the original result
will be used. The URL and method can actually be configured by you.

### Request Body

* collection of `\Frontastic\Common\ProductApiBundle\Domain\Category`

### Responses

Status: 200

Adapt the categories result. If nothing is returned the original result will
be used. The URL and method can actually be configured by you.

* *optional* collection of `\Frontastic\Common\ProductApiBundle\Domain\Category`

## `POST` `https://example.com/beforeQuery`

*Before Decorator for query*

Adapt the product query before the query is executed against the
backend. If nothing is returned the original arguments will be used.
The URL and method can actually be configured by you.

### Request Body

* tuple (array containing):

  * `\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery`

### Responses

Status: 200

Adapt the product query before the query is executed against the backend. If
nothing is returned the original arguments will be used. The URL and method
can actually be configured by you.

* *optional* tuple (array containing):

    * `\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery`

## `POST` `https://example.com/afterQuery`

*After Decorator for query*

Adapt the product query result. If nothing is returned the original
result will be used. The URL and method can actually be configured by
you.

### Request Body

* *optional* `\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result` with:

    * `items` as collection of `\Frontastic\Common\ProductApiBundle\Domain\Product`

### Responses

Status: 200

Adapt the product query result. If nothing is returned the original result
will be used. The URL and method can actually be configured by you.

* *optional* `\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result` with:

    * `items` as collection of `\Frontastic\Common\ProductApiBundle\Domain\Product`

