#  DataSourceContext

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceContext`](../../../../../src/php/NextJsBundle/Domain/Api/DataSourceContext.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

All fields in the context are optional. We want to introduce a mechanism in
the future that allows extensions to annotate which context data they require.
Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`frontasticContext` | [`Context`](Context.md) | `null` | - | 
`pageFolder` | [`PageFolder`](PageFolder.md) | `null` | - | The page folder being rendered.
`page` | [`Page`](Page.md) | `null` | - | The page being rendered.
`usingTastics` | [`Tastic`](Tastic.md)[]|null | `null` | - | Tastics on the page which are using this data source.
`request` | [`Request`](Request.md) | `null` | - | 

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
