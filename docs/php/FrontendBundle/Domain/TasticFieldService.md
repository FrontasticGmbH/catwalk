#  TasticFieldService

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldService`](../../../../src/php/FrontendBundle/Domain/TasticFieldService.php)

## Methods

* [__construct()](#__construct)
* [getFieldData()](#getfielddata)

### __construct()

```php
public function __construct(
    TasticService $tasticDefinitionService,
    iterable $fieldHandlers = []
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$tasticDefinitionService`|[`TasticService`](../../ApiCoreBundle/Domain/TasticService.md)||
`$fieldHandlers`|`iterable`|`[]`|

Return Value: `mixed`

### getFieldData()

```php
public function getFieldData(
    Context $context,
    Node $node,
    Page $page
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||
`$node`|[`Node`](Node.md)||
`$page`|[`Page`](Page.md)||

Return Value: `array`

