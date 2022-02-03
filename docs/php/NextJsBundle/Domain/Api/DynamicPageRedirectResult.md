#  DynamicPageRedirectResult

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageRedirectResult`](../../../../../src/php/NextJsBundle/Domain/Api/DynamicPageRedirectResult.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

**Implements**: [`DynamicPageResult`](DynamicPageResult.md)

This is, for example, useful to update the URL of a product detail page when
the SEO slug of the product changes.
Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`redirectLocation` | `string` |  | *Yes* | 
`statusCode` | `int` | `301` | - | 
`statusMessage` | `string` | `null` | - | Allows to override the standard HTTP status message.
`additionalResponseHeaders` | `array<string, string>` | `[]` | - | Allows specifying additional headers for the redirect.

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
