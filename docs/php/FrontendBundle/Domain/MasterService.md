#  MasterService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\MasterService`](../../../../src/php/FrontendBundle/Domain/MasterService.php)

**Implements**: `\Frontastic\Common\ReplicatorBundle\Domain\Target`

## Methods

* [__construct()](#__construct)
* [matchNodeId()](#matchnodeid)
* [completeDefaultQuery()](#completedefaultquery)
* [completeTasticStreamConfigurationWithMasterDefault()](#completetasticstreamconfigurationwithmasterdefault)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\MasterPageMatcherRulesGateway $rulesGateway,
    TasticService $tasticService,
    \RulerZ\RulerZ $rulerz
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$rulesGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\MasterPageMatcherRulesGateway`||
`$tasticService`|[`TasticService`](../../ApiCoreBundle/Domain/TasticService.md)||
`$rulerz`|`\RulerZ\RulerZ`||

Return Value: `mixed`

### matchNodeId()

```php
public function matchNodeId(
    PageMatcherContext $context
): string
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`PageMatcherContext`](PageMatcher/PageMatcherContext.md)||

Return Value: `string`

### completeDefaultQuery()

```php
public function completeDefaultQuery(
    array $streams,
    string $pageType,
    ?string $itemId
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$streams`|`array`||
`$pageType`|`string`||
`$itemId`|`?string`||

Return Value: `array`

### completeTasticStreamConfigurationWithMasterDefault()

```php
public function completeTasticStreamConfigurationWithMasterDefault(
    Page $page,
    string $pageType
): void
```

*Fixes that Backstage does not send proper __master for singleton master pages.*

This should eventually be fixed in Backstage, but requires a migration phase.

Argument|Type|Default|Description
--------|----|-------|-----------
`$page`|[`Page`](Page.md)||
`$pageType`|`string`||

Return Value: `void`

### lastUpdate()

```php
public function lastUpdate(): string
```

Return Value: `string`

### replicate()

```php
public function replicate(
    array $updates
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$updates`|`array`||

Return Value: `void`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
