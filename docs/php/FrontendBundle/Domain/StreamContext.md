#  StreamContext

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\StreamContext`](../../../../src/php/FrontendBundle/Domain/StreamContext.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Description
--------|----|-------|-----------
`node`|[`Node`](Node.md)||
`page`|[`Page`](Page.md)||
`context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||
`usingTastics`|[`Tastic`](Tastic.md)[]|`[]`|
`parameters`|`array`|`[]`|Parameters given to the stream in the current request context.

