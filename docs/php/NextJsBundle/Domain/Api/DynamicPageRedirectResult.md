#  DynamicPageRedirectResult

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageRedirectResult`](../../../../../src/php/NextJsBundle/Domain/Api/DynamicPageRedirectResult.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

**Implements**: [`DynamicPageResult`](DynamicPageResult.md)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`redirectLocation` | `` |  | - | 
`statusCode` | `` | `301` | - | 
`statusMessage` | `string` | `null` | - | Allows to override the standard HTTP status message.
`additionalResponseHeaders` | `array<string, string>` | `[]` | - | Allows to specify additional headers for the redirect.

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
