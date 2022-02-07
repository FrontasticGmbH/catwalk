#  RedirectService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\RedirectService`](../../../../src/php/FrontendBundle/Domain/RedirectService.php)

**Implements**: `\Frontastic\Common\ReplicatorBundle\Domain\Target`

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)
* [get()](#get)
* [getRedirectUrlForRequest()](#getredirecturlforrequest)
* [getRedirects()](#getredirects)
* [getRedirectForRequest()](#getredirectforrequest)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\RedirectGateway $redirectGateway,
    \Symfony\Component\Routing\Router $router
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$redirectGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\RedirectGateway`||
`$router`|`\Symfony\Component\Routing\Router`||

Return Value: `mixed`

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

### get()

```php
public function get(
    string $redirectId
): Redirect
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$redirectId`|`string`||

Return Value: [`Redirect`](Redirect.md)

### getRedirectUrlForRequest()

```php
public function getRedirectUrlForRequest(
    string $path,
    \Symfony\Component\HttpFoundation\ParameterBag $queryParameters,
    Context $context
): ?object
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$path`|`string`||
`$queryParameters`|`\Symfony\Component\HttpFoundation\ParameterBag`||
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||

Return Value: `?object`

### getRedirects()

```php
public function getRedirects(): array
```

Return Value: `array`

### getRedirectForRequest()

```php
public function getRedirectForRequest(
    string $path,
    \Symfony\Component\HttpFoundation\ParameterBag $queryParameters
): ?Redirect
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$path`|`string`||
`$queryParameters`|`\Symfony\Component\HttpFoundation\ParameterBag`||

Return Value: ?[`Redirect`](Redirect.md)

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
