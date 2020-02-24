#  CategoriesContain

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\RulerZ\Operator\CategoriesContain`](../../../../../src/php/FrontendBundle/RulerZ/Operator/CategoriesContain.php)

## Methods

* [__construct()](#__construct)
* [__invoke()](#__invoke)

### __construct()

```php
public function __construct(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi $productApi,
    Context $context
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$productApi`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi`||
`$context`|[`Context`](../../../ApiCoreBundle/Domain/Context.md)||

Return Value: `mixed`

### __invoke()

```php
public function __invoke(
    array $categoryIds,
    string $ancestorId
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$categoryIds`|`array`||
`$ancestorId`|`string`||

Return Value: `mixed`

