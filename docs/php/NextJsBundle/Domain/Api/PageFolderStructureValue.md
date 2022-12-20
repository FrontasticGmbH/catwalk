#  PageFolderStructureValue

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolderStructureValue`](../../../../../src/php/NextJsBundle/Domain/Api/PageFolderStructureValue.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`pageFolderId` | `string` |  | *Yes* | 
`pageFolderType` | `string` | `'landingpage'` | *Yes* | 
`configuration` | `array` | `[]` | *Yes* | 
`name` | `string` |  | - | 
`ancestorIdsMaterializedPath` | `string` |  | *Yes* | Materialized path of IDs of ancestor page folders.
`depth` | `int` |  | *Yes* | Depth of this page folder in the page folder tree.
`sort` | `int` |  | *Yes* | Sort order in the page folder tree.
`breadcrumbs` | [`PageFolderBreadcrumb`](PageFolderBreadcrumb.md)[] | `[]` | - | 
`_urls` | `array` |  | - | 
`_url` | `string` |  | - | The url for the current locale

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
