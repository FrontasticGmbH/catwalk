#  Response

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\Response`](../../../../../src/php/NextJsBundle/Domain/Api/Response.php)

The response structure is inspired by Express.js version 4.x + Frontastic
sessionData. IMPORTANT: To retain session information you need to return the
session that comes in through sessionData in a request in the response of the
action.
Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`statusCode` | `string` |  | *Yes* | 
`body` | `string` |  | - | 
`sessionData` | `?object` |  | - | Frontastic session data to be written.

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
