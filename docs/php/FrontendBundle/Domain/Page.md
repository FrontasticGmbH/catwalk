#  Page

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\Domain\Page`](../../../../src/php/FrontendBundle/Domain/Page.php)

Property|Type|Default|Description
--------|----|-------|-----------
`pageId`|`string`||
`sequence`|`string`||
`node`|`Node`||
`layoutId`|`string`||
`regions`|`Region[]`|`[]`|
`metaData`|`\Frontastic\UserBundle\Domain\MetaData`||
`isDeleted`|`bool`|`false`|
`state`|`string`||
`scheduledFromTimestamp`|`int|null`||This is a UNIX timestamp since doctrine can not persist a \DateTime-object to MySQL and ensure the time point is still the same. It can ensure to maintain the time but the timezone may change which produces a different time point.
`scheduledToTimestamp`|`int|null`||This is a UNIX timestamp since doctrine can not persist a \DateTime-object to MySQL and ensure the time point is still the same. It can ensure to maintain the time but the timezone may change which produces a different time point.
`nodesPagesOfTypeSortIndex`|`int|null`|`null`|
`scheduleCriterion`|`string`|`''`|A FECL criterion which can control when this page will be rendered if it is in the scheduled state.

