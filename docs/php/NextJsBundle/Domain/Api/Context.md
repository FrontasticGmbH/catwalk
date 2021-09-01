#  Context

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\Context`](../../../../../src/php/NextJsBundle/Domain/Api/Context.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`environment` | `int` | `'production'` | - | Result of {@link Frontastic\Catwalk\ApiCoreBundle\Domain\Context.applicationEnvironment()}
`project` | [`Project`](Project.md) |  | *Yes* | 
`projectConfiguration` | `array` | `[]` | *Yes* | 
`locale` | `string` |  | *Yes* | 
`featureFlags` | `array<string, bool>` | `[]` | - | 

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
