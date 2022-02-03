#  Context

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\Context`](../../../../../src/php/NextJsBundle/Domain/Api/Context.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Includes environment information configuration on ba
Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`environment` | `string` | `'production'` | *Yes* | One of "production", "staging" or "development".
`project` | [`Project`](Project.md) |  | *Yes* | 
`projectConfiguration` | `array` | `[]` | *Yes* | Additional project configuration from Frontastic studio.
`locale` | `string` |  | *Yes* | The currently set locale by the user in the frontend.
`featureFlags` | `array<string, bool>` | `[]` | *Yes* | Feature flags mapped to their state.

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
