#  Redirect

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\Redirect`](../../../../src/php/FrontendBundle/Domain/Redirect.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`redirectId` | `string` |  | *Yes* | 
`sequence` | `string` |  | *Yes* | 
`path` | `string` |  | *Yes* | 
`query` | `string` |  | - | 
`statusCode` | `int` | `301` | - | 
`targetType` | `string` |  | *Yes* | One of TARGET_TYPE_* constants
`target` | `string` |  | *Yes* | 
`language` | `?string` | `null` | - | 
`metaData` | `\Frontastic\Backstage\UserBundle\Domain\MetaData` |  | *Yes* | 
`isDeleted` | `bool` | `false` | *Yes* | 

## Methods

* [getQueryParameters()](#getqueryparameters)

### getQueryParameters()

```php
public function getQueryParameters(): \Symfony\Component\HttpFoundation\ParameterBag
```

Return Value: `\Symfony\Component\HttpFoundation\ParameterBag`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
