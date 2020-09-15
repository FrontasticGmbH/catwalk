#  StreamContext

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\StreamContext`](../../../../src/php/FrontendBundle/Domain/StreamContext.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`node` | [`Node`](Node.md) |  | *Yes* | 
`page` | [`Page`](Page.md) |  | *Yes* | 
`context` | [`Context`](../../ApiCoreBundle/Domain/Context.md) |  | *Yes* | 
`usingTastics` | [`Tastic`](Tastic.md)[] | `[]` | *Yes* | 
`parameters` | `array` | `[]` | *Yes* | Parameters given to the stream in the current request context.

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
