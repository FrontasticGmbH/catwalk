#  HooksService

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService`](../../../../../src/php/ApiCoreBundle/Domain/Hooks/HooksService.php)

## Methods

* [__construct()](#__construct)
* [callExpectArray()](#callexpectarray)
* [callExpectList()](#callexpectlist)
* [callExpectObject()](#callexpectobject)
* [callExpectMultipleObjects()](#callexpectmultipleobjects)

### __construct()

```php
public function __construct(
    HooksApiClient $hooksApiClient,
    \Frontastic\Common\JsonSerializer $jsonSerializer,
    HookResponseDeserializer $hookResponseDeserializer,
    ContextService $contextService,
    \Symfony\Component\HttpFoundation\RequestStack $requestStack,
    \Psr\Log\LoggerInterface $logger
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$hooksApiClient`|[`HooksApiClient`](HooksApiClient.md)||
`$jsonSerializer`|`\Frontastic\Common\JsonSerializer`||
`$hookResponseDeserializer`|[`HookResponseDeserializer`](HookResponseDeserializer.md)||
`$contextService`|[`ContextService`](../ContextService.md)||
`$requestStack`|`\Symfony\Component\HttpFoundation\RequestStack`||
`$logger`|`\Psr\Log\LoggerInterface`||

Return Value: `mixed`

### callExpectArray()

```php
public function callExpectArray(
    string $hook,
    array $arguments
): ?array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$hook`|`string`||
`$arguments`|`array`||

Return Value: `?array`

### callExpectList()

```php
public function callExpectList(
    string $hook,
    array $arguments
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$hook`|`string`||
`$arguments`|`array`||

Return Value: `mixed`

### callExpectObject()

```php
public function callExpectObject(
    string $hook,
    array $arguments
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$hook`|`string`||
`$arguments`|`array`||

Return Value: `mixed`

### callExpectMultipleObjects()

```php
public function callExpectMultipleObjects(
    string $hook,
    array $arguments
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$hook`|`string`||
`$arguments`|`array`||

Return Value: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
