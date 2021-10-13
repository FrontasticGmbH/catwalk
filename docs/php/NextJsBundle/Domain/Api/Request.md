#  Request

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\Request`](../../../../../src/php/NextJsBundle/Domain/Api/Request.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

{@see https://expressjs.com/en/api.html#req}
Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`body` | `string` |  | - | Will be JSON-decoded on the JS side and hold an object there.
`cookies` | `array<string, string>` |  | - | <cookie-name> -> <cookie-value>
`hostname` | `string` |  | - | 
`method` | `string` |  | - | 
`path` | `string` |  | - | 
`query` | `object` |  | - | 
`sessionData` | `?object` | `null` | - | Frontastic session data.

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
