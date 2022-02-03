#  PageFolder

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolder`](../../../../../src/php/NextJsBundle/Domain/Api/PageFolder.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`pageFolderId` | `string` |  | *Yes* | 
`isDynamic` | `bool` | `false` | *Yes* | 
`pageFolderType` | `string` | `'landingpage'` | *Yes* | 
`configuration` | `array` | `[]` | *Yes* | 
`dataSourceConfigurations` | [`DataSourceConfiguration`](DataSourceConfiguration.md)[] | `[]` | *Yes* | 
`name` | `string` |  | - | 
`ancestorIdsMaterializedPath` | `string` |  | *Yes* | Materialized path of IDs of ancestor page folders.
`depth` | `int` |  | *Yes* | Depth of this page folder in the page folder tree.
`sort` | `int` |  | *Yes* | Sort order in the page folder tree.

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
