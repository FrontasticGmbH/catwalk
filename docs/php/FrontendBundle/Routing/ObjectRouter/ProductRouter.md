#  ProductRouter

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ProductRouter`](../../../../../src/php/FrontendBundle/Routing/ObjectRouter/ProductRouter.php)

## Methods

* [__construct()](#__construct)
* [generateUrlFor()](#generateurlfor)
* [identifyFrom()](#identifyfrom)

### __construct()

```php
public function __construct(
    \Symfony\Component\DependencyInjection\ContainerInterface $container
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$container`|`\Symfony\Component\DependencyInjection\ContainerInterface`||

Return Value: `mixed`

### generateUrlFor()

```php
public function generateUrlFor(
    \Frontastic\Common\ProductApiBundle\Domain\Product $product
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$product`|`\Frontastic\Common\ProductApiBundle\Domain\Product`||

Return Value: `mixed`

### identifyFrom()

```php
public function identifyFrom(
    \Symfony\Component\HttpFoundation\Request $request,
    Context $context
): ?string
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$request`|`\Symfony\Component\HttpFoundation\Request`||
`$context`|[`Context`](../../../ApiCoreBundle/Domain/Context.md)||

Return Value: `?string`

