# `abstract`  StreamHandler

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler`](../../../../src/php/FrontendBundle/Domain/StreamHandler.php)

## Methods

* [getType()](#gettype)
* [handleAsync()](#handleasync)

### getType()

```php
abstract public function getType(): string
```

Return Value: `string`

### handleAsync()

```php
public function handleAsync(
    Stream $stream,
    Context $context,
    array $parameters = []
): \GuzzleHttp\Promise\PromiseInterface
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$stream`|[`Stream`](Stream.md)||
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||
`$parameters`|`array`|`[]`|

Return Value: `\GuzzleHttp\Promise\PromiseInterface`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
