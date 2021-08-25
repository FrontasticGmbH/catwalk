#  DynamicPageSuccessResult

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageSuccessResult`](../../../../../src/php/NextJsBundle/Domain/Api/DynamicPageSuccessResult.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

**Implements**: [`DynamicPageResult`](DynamicPageResult.md)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`dynamicPageType` | `string` |  | - | Unique identifier for the page type matched. Will correlate with configuration in Frontastic studio.
`dataSourcePayload` | `mixed` |  | - | Payload for the main data source of the dynamic page.
`pageMatchingPayload` | `object` |  | - | Submit a payload we use for page matching (FECL!)

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
