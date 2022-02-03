#  DynamicPageSuccessResult

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageSuccessResult`](../../../../../src/php/NextJsBundle/Domain/Api/DynamicPageSuccessResult.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

**Implements**: [`DynamicPageResult`](DynamicPageResult.md)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`dynamicPageType` | `string` |  | *Yes* | Unique identifier for the page type matched. Will correlate with configuration in Frontastic studio.
`dataSourcePayload` | `mixed` |  | *Yes* | Payload for the main (__master) data source of the dynamic page.
`pageMatchingPayload` | `mixed` |  | - | Submit a payload Frontastic uses for scheduled page criterion matching (FECL)

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
