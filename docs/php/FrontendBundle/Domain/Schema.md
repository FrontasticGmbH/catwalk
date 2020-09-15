#  Schema

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\Schema`](../../../../src/php/FrontendBundle/Domain/Schema.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`schemaId` | `string` |  | *Yes* | 
`schemaType` | `string` |  | *Yes* | 
`schema` | `array` |  | *Yes* | 
`metaData` | `\Frontastic\Backstage\UserBundle\Domain\MetaData` |  | *Yes* | 
`sequence` | `string` |  | *Yes* | 
`isDeleted` | `bool` | `false` | *Yes* | 

## Methods

* [getSchemaConfiguration()](#getschemaconfiguration)

### getSchemaConfiguration()

```php
public function getSchemaConfiguration(): array
```

Return Value: `array`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
