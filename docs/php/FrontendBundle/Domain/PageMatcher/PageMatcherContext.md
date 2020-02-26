#  PageMatcherContext

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext`](../../../../../src/php/FrontendBundle/Domain/PageMatcher/PageMatcherContext.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Description
--------|----|-------|-----------
`entity`|`object|null`||
`categoryId`|`string|null`||
`productId`|`string|null`||
`contentId`|`string|null`||
`search`|`?string`||
`cart`|`object|null`||
`checkout`|`object|null`||
`checkoutFinished`|`object|null`||
`orderId`|`string|null`||
`account`|`object|null`||
`accountForgotPassword`|`object|null`||
`accountConfirm`|`object|null`||
`accountProfile`|`object|null`||
`accountAddresses`|`object|null`||
`accountOrders`|`object|null`||
`accountWishlists`|`object|null`||
`accountVouchers`|`object|null`||
`error`|`object|null`||

## Methods

* [productPage()](#productpage)

### productPage()

```php
static public function productPage(
    mixed $productId
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$productId`|`mixed`||

Return Value: `mixed`

