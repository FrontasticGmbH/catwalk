#  Node

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\Node`](../../../../src/php/FrontendBundle/Domain/Node.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Description
--------|----|-------|-----------
`nodeId`|`string`||
`isMaster`|`bool`|`false`|
`sequence`|`string`||
`configuration`|`array`|`[]`|
`streams`|`Stream[]`|`[]`|
`name`|`string`||
`path`|`string[]`|`[]`|
`depth`|`int`||
`sort`|`int`||
`children`|`Node[]`|`[]`|
`metaData`|`\Frontastic\Backstage\UserBundle\Domain\MetaData`||
`error`|`?string`||Optional error string during development
`isDeleted`|`bool`|`false`|

