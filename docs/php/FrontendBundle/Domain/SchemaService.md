#  SchemaService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\SchemaService`](../../../../src/php/FrontendBundle/Domain/SchemaService.php)

**Implements**: `\Frontastic\Common\ReplicatorBundle\Domain\Target`, [`ContextDecorator`](../../ApiCoreBundle/Domain/ContextDecorator.md)

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)
* [decorate()](#decorate)
* [completeNodeData()](#completenodedata)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\SchemaGateway $schemaGateway
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$schemaGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\SchemaGateway`||

Return Value: `mixed`

### lastUpdate()

```php
public function lastUpdate(): string
```

Return Value: `string`

### replicate()

```php
public function replicate(
    array $updates
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$updates`|`array`||

Return Value: `void`

### decorate()

```php
public function decorate(
    Context $context
): Context
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||

Return Value: [`Context`](../../ApiCoreBundle/Domain/Context.md)

### completeNodeData()

```php
public function completeNodeData(
    Node $node,
    ?\Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor $fieldVisitor = null
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$node`|[`Node`](Node.md)||
`$fieldVisitor`|`?\Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor`|`null`|

Return Value: `void`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
