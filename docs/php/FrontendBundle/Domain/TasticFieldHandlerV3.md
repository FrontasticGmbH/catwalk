# `abstract`  TasticFieldHandlerV3

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandlerV3`](../../../../src/php/FrontendBundle/Domain/TasticFieldHandlerV3.php)

## Methods

* [getType()](#gettype)
* [handle()](#handle)

### getType()

```php
abstract public function getType(): string
```

Return Value: `string`

### handle()

```php
abstract public function handle(
    Context $context,
    Node $node,
    Page $page,
    Tastic $tastic,
    mixed $fieldValue
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||
`$node`|[`Node`](Node.md)||
`$page`|[`Page`](Page.md)||
`$tastic`|[`Tastic`](Tastic.md)||
`$fieldValue`|`mixed`||

Return Value: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
