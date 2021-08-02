#  StreamHandlerV2Adapter

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\StreamHandlerV2Adapter`](../../../../src/php/FrontendBundle/Domain/StreamHandlerV2Adapter.php)

**Implements**: [`StreamHandlerV2`](StreamHandlerV2.md)

## Methods

* [__construct()](#__construct)
* [handle()](#handle)

### __construct()

```php
public function __construct(
    StreamHandler $innerHandler
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$innerHandler`|[`StreamHandler`](StreamHandler.md)||

Return Value: `mixed`

### handle()

```php
public function handle(
    Stream $stream,
    StreamContext $streamContext
): \GuzzleHttp\Promise\PromiseInterface
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$stream`|[`Stream`](Stream.md)||
`$streamContext`|[`StreamContext`](StreamContext.md)||

Return Value: `\GuzzleHttp\Promise\PromiseInterface`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
