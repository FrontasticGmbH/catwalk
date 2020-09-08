#  AccountWishlistsFieldHandler

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler\AccountWishlistsFieldHandler`](../../../../../src/php/FrontendBundle/Domain/TasticFieldHandler/AccountWishlistsFieldHandler.php)

**Extends**: [`TasticFieldHandler`](../TasticFieldHandler.md)

## Methods

* [__construct()](#__construct)
* [getType()](#gettype)
* [handle()](#handle)

### __construct()

```php
public function __construct(
    \Frontastic\Common\WishlistApiBundle\Domain\WishlistApi $wishlistApi
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$wishlistApi`|`\Frontastic\Common\WishlistApiBundle\Domain\WishlistApi`||

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

