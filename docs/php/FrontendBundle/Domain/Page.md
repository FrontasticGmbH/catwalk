#  Page

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\Page`](../../../../src/php/FrontendBundle/Domain/Page.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`pageId` | `string` |  | *Yes* | 
`sequence` | `string` |  | *Yes* | 
`node` | `string` |  | *Yes* | 
`layoutId` | `string` |  | - | 
`regions` | [`Region`](Region.md)[] | `[]` | *Yes* | 
`metaData` | `\Frontastic\UserBundle\Domain\MetaData` |  | *Yes* | 
`isDeleted` | `bool` | `false` | *Yes* | 
`state` | `string` |  | *Yes* | 
`scheduledFromTimestamp` | `?int` |  | - | This is a UNIX timestamp since doctrine can not persist a \DateTime-object to MySQL and ensure the time point is still the same. It can ensure to maintain the time but the timezone may change which produces a different time point.
`scheduledToTimestamp` | `?int` |  | - | This is a UNIX timestamp since doctrine can not persist a \DateTime-object to MySQL and ensure the time point is still the same. It can ensure to maintain the time but the timezone may change which produces a different time point.
`nodesPagesOfTypeSortIndex` | `?int` | `null` | - | 
`scheduleCriterion` | `string` | `''` | - | A FECL criterion which can control when this page will be rendered if it is in the scheduled state.
`scheduledExperiment` | `?string` | `null` | - | An experiment ID from a third party system like Kameleoon

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
