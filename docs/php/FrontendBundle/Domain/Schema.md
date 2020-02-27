#  Schema

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\Schema`](../../../../src/php/FrontendBundle/Domain/Schema.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Description
--------|----|-------|-----------
`schemaId`|`string`||
`schemaType`|`string`||
`schema`|`array`||
`metaData`|`\Frontastic\Backstage\UserBundle\Domain\MetaData`||
`sequence`|`string`||
`isDeleted`|`bool`|`false`|

## Methods

* [getSchemaConfiguration()](#getschemaconfiguration)

### getSchemaConfiguration()

```php
public function getSchemaConfiguration(): array
```

Return Value: `array`

