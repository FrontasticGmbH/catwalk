#  Node

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\Node`](../../../../src/php/FrontendBundle/Domain/Node.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`nodeId` | `string` |  | *Yes* | 
`isMaster` | `bool` | `false` | *Yes* | 
`nodeType` | `string` | `'landingpage'` | *Yes* | 
`sequence` | `string` |  | *Yes* | 
`configuration` | `array` | `[]` | *Yes* | 
`streams` | [`Stream`](Stream.md)[] | `[]` | *Yes* | 
`name` | `string` |  | - | 
`path` | `string` | `''` | *Yes* | 
`depth` | `int` |  | - | 
`sort` | `int` |  | *Yes* | 
`children` | [`Node`](Node.md)[] | `[]` | *Yes* | 
`metaData` | `\Frontastic\Backstage\UserBundle\Domain\MetaData` |  | *Yes* | 
`error` | `?string` |  | - | Optional error string during development
`hasLivePage` | `bool` | `false` | - | Page is live
`isDeleted` | `bool` | `false` | *Yes* | 

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
