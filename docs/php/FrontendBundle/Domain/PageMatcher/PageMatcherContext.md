#  PageMatcherContext

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext`](../../../../../src/php/FrontendBundle/Domain/PageMatcher/PageMatcherContext.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Description
--------|----|-------|-----------
`entity`|`?object`||
`categoryId`|`?string`||
`productId`|`?string`||
`contentId`|`?string`||
`search`|`?string`||
`cart`|`?object`||
`checkout`|`?object`||
`checkoutFinished`|`?object`||
`orderId`|`?string`||
`account`|`?object`||
`accountForgotPassword`|`?object`||
`accountConfirm`|`?object`||
`accountProfile`|`?object`||
`accountAddresses`|`?object`||
`accountOrders`|`?object`||
`accountWishlists`|`?object`||
`accountVouchers`|`?object`||
`error`|`?object`||

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

