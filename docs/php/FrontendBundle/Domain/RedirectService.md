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

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\RedirectGateway $redirectGateway,
    \Symfony\Component\Routing\Router $router,
    Context $context
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$redirectGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\RedirectGateway`||
`$router`|`\Symfony\Component\Routing\Router`||
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||

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
    \Symfony\Component\HttpFoundation\ParameterBag $queryParameters
): ?string
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$path`|`string`||
`$queryParameters`|`\Symfony\Component\HttpFoundation\ParameterBag`||

Return Value: `?string`

### getRedirects()

```php
public function getRedirects(): array
```

Return Value: `array`

