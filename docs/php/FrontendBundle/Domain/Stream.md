#  Stream

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\Stream`](../../../../src/php/FrontendBundle/Domain/Stream.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`streamId` | `string` |  | *Yes* | 
`type` | `string` |  | *Yes* | 
`name` | `string` |  | *Yes* | 
`configuration` | `array` | `[]` | *Yes* | 
`tastics` | [`Tastic`](Tastic.md)[] | `[]` | *Yes* | 
`preloadedValue` | `mixed` | `null` | - | If a stream value was pre-loaded before executing actual stream handlers, the value will be contained here.

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
