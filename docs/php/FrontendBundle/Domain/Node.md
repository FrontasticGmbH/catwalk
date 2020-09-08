#  Node

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\Node`](../../../../src/php/FrontendBundle/Domain/Node.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`nodeId` | `string` |  | - | 
`isMaster` | `bool` | `false` | - | 
`nodeType` | `string` | `'landingpage'` | - | 
`sequence` | `string` |  | - | 
`configuration` | `array` | `[]` | - | 
`streams` | [`Stream`](Stream.md)[] | `[]` | - | 
`name` | `string` |  | - | 
`path` | `string[]` | `[]` | - | 
`depth` | `int` |  | - | 
`sort` | `int` |  | - | 
`children` | [`Node`](Node.md)[] | `[]` | - | 
`metaData` | `\Frontastic\Backstage\UserBundle\Domain\MetaData` |  | - | 
`error` | `?string` |  | - | Optional error string during development
`isDeleted` | `bool` | `false` | - | 

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
