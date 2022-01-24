#  Request

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\Request`](../../../../../src/php/NextJsBundle/Domain/Api/Request.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

The request structure is inspired by Express.js version 4.x and contains
additional Frontastic $sessionData.
Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`body` | `string` |  | - | Will be JSON-decoded on the JS side and hold an object there.
`cookies` | `array<string, string>` |  | - | <cookie-name> -> <cookie-value>
`hostname` | `string` |  | - | 
`method` | `string` |  | *Yes* | 
`path` | `string` |  | *Yes* | 
`query` | `object` |  | *Yes* | 
`sessionData` | `?object` | `null` | - | Frontastic session data.

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
