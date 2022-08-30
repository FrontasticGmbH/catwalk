#  DataSourceResult

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceResult`](../../../../../src/php/NextJsBundle/Domain/Api/DataSourceResult.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Different data source implementations
Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`dataSourcePayload` | `mixed` |  | *Yes* | Arbitrary (JSON serializable) payload information returned by the data source.
`previewPayload` | [`DataSourcePreviewPayloadElement`](DataSourcePreviewPayloadElement.md)[] | `[]` | - | Studio will get the data when showing the data source previews from this array.

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
