#  TreeFieldHandler

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler\TreeFieldHandler`](../../../../../src/php/FrontendBundle/Domain/TasticFieldHandler/TreeFieldHandler.php)

**Extends**: [`TasticFieldHandler`](../TasticFieldHandler.md)

## Methods

* [__construct()](#__construct)
* [getType()](#gettype)
* [handle()](#handle)

### __construct()

```php
public function __construct(
    NodeService $nodeService,
    \Psr\Log\LoggerInterface $logger
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$nodeService`|[`NodeService`](../NodeService.md)||
`$logger`|`\Psr\Log\LoggerInterface`||

Return Value: `mixed`

### getType()

```php
public function getType(): string
```

Return Value: `string`

### handle()

```php
public function handle(
    Context $context,
    mixed $fieldValue
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`Context`](../../../ApiCoreBundle/Domain/Context.md)||
`$fieldValue`|`mixed`||

Return Value: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
