#  Redirect

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\Redirect`](../../../../src/php/FrontendBundle/Domain/Redirect.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Description
--------|----|-------|-----------
`redirectId`|`string`||
`sequence`|`string`||
`path`|`string`||
`query`|`string`||
`targetType`|`string`||
`target`|`string`||
`language`|`string|null`|`null`|
`metaData`|`\Frontastic\Backstage\UserBundle\Domain\MetaData`||
`isDeleted`|`bool`|`false`|

## Methods

* [getQueryParameters()](#getqueryparameters)

### getQueryParameters()

```php
public function getQueryParameters(): \Symfony\Component\HttpFoundation\ParameterBag
```

Return Value: `\Symfony\Component\HttpFoundation\ParameterBag`

