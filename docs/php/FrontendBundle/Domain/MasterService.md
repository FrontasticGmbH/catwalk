#  MasterService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\MasterService`](../../../../src/php/FrontendBundle/Domain/MasterService.php)

**Implements**: `\Frontastic\Common\ReplicatorBundle\Domain\Target`

## Methods

* [__construct()](#__construct)
* [matchNodeId()](#matchnodeid)
* [completeDefaultQuery()](#completedefaultquery)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\MasterPageMatcherRulesGateway $rulesGateway,
    \RulerZ\RulerZ $rulerz
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$rulesGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\MasterPageMatcherRulesGateway`||
`$rulerz`|`\RulerZ\RulerZ`||

Return Value: `mixed`

### matchNodeId()

```php
public function matchNodeId(
    PageMatcherContext $context
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`PageMatcherContext`](PageMatcher/PageMatcherContext.md)||

Return Value: `mixed`

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

