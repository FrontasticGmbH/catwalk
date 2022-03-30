#  ExtensionService

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService`](../../../../../src/php/ApiCoreBundle/Domain/Hooks/ExtensionService.php)

## Methods

* [__construct()](#__construct)
* [fetchProjectExtensions()](#fetchprojectextensions)
* [getExtensions()](#getextensions)
* [hasExtension()](#hasextension)
* [hasDynamicPageHandler()](#hasdynamicpagehandler)
* [hasAction()](#hasaction)
* [callDataSource()](#calldatasource)
* [callDynamicPageHandler()](#calldynamicpagehandler)
* [callAction()](#callaction)

### __construct()

```php
public function __construct(
    \Psr\Log\LoggerInterface $logger,
    ContextService $contextService,
    \Symfony\Component\HttpFoundation\RequestStack $requestStack,
    \Frontastic\Common\HttpClient $httpClient
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$logger`|`\Psr\Log\LoggerInterface`||
`$contextService`|[`ContextService`](../ContextService.md)||
`$requestStack`|`\Symfony\Component\HttpFoundation\RequestStack`||
`$httpClient`|`\Frontastic\Common\HttpClient`||

Return Value: `mixed`

### fetchProjectExtensions()

```php
public function fetchProjectExtensions(
    string $project
): array
```

*Fetches the extension list from the extension runner*

Argument|Type|Default|Description
--------|----|-------|-----------
`$project`|`string`||

Return Value: `array`

### getExtensions()

```php
public function getExtensions(): array
```

*Gets the list of extensions*

If the list does not exist yet, it will be fetched automatically from the extension runner

Return Value: `array`

### hasExtension()

```php
public function hasExtension(
    string $extensionName
): bool
```

*Check if extension exists*

Argument|Type|Default|Description
--------|----|-------|-----------
`$extensionName`|`string`||

Return Value: `bool`

### hasDynamicPageHandler()

```php
public function hasDynamicPageHandler(): bool
```

*Checks if the dynamic page handler extension exists*

Return Value: `bool`

### hasAction()

```php
public function hasAction(
    mixed $namespace,
    mixed $action
): bool
```

*Check if the specified action extension exists*

Argument|Type|Default|Description
--------|----|-------|-----------
`$namespace`|`mixed`||
`$action`|`mixed`||

Return Value: `bool`

### callDataSource()

```php
public function callDataSource(
    string $extensionName,
    array $arguments,
    ?int $timeout
): \GuzzleHttp\Promise\PromiseInterface
```

*Calls a datasource extension*

Argument|Type|Default|Description
--------|----|-------|-----------
`$extensionName`|`string`||
`$arguments`|`array`||
`$timeout`|`?int`||

Return Value: `\GuzzleHttp\Promise\PromiseInterface`

### callDynamicPageHandler()

```php
public function callDynamicPageHandler(
    array $arguments,
    ?int $timeout
): ?object
```

*Calls a dynamic page handler extension*

Argument|Type|Default|Description
--------|----|-------|-----------
`$arguments`|`array`||
`$timeout`|`?int`||

Return Value: `?object`

### callAction()

```php
public function callAction(
    string $namespace,
    string $action,
    array $arguments,
    ?int $timeout
): mixed
```

*Calls an action*

Argument|Type|Default|Description
--------|----|-------|-----------
`$namespace`|`string`||
`$action`|`string`||
`$arguments`|`array`||
`$timeout`|`?int`||

Return Value: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
