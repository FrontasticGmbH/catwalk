#  ContextService

Fully Qualified: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService`](../../../../src/php/ApiCoreBundle/Domain/ContextService.php)

## Methods

* [__construct()](#__construct)
* [addDecorator()](#adddecorator)
* [createContextFromRequest()](#createcontextfromrequest)
* [getContext()](#getcontext)
* [getSession()](#getsession)

### __construct()

```php
public function __construct(
    \Symfony\Bundle\FrameworkBundle\Routing\Router $router,
    \Symfony\Component\HttpFoundation\RequestStack $requestStack,
    CustomerService $customerService,
    ProjectService $projectService,
    \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage,
    LocaleResolver $localeResolver,
    iterable $decorators
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$router`|`\Symfony\Bundle\FrameworkBundle\Routing\Router`||
`$requestStack`|`\Symfony\Component\HttpFoundation\RequestStack`||
`$customerService`|[`CustomerService`](CustomerService.md)||
`$projectService`|[`ProjectService`](ProjectService.md)||
`$tokenStorage`|`\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface`||
`$localeResolver`|[`LocaleResolver`](Context/LocaleResolver.md)||
`$decorators`|`iterable`||

Return Value: `mixed`

### addDecorator()

```php
public function addDecorator(
    ContextDecorator $decorator
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$decorator`|[`ContextDecorator`](ContextDecorator.md)||

Return Value: `void`

### createContextFromRequest()

```php
public function createContextFromRequest(
    \Symfony\Component\HttpFoundation\Request $request = null
): Context
```

*Creates the Context for a given request. Falls back to current request.*

If no $request is provided the method falls back to the current request from the RequestStack. NOTE: This is
not recommended. If possible, please get hold on the Request you want to react on instead of relying on this
magic!

Argument|Type|Default|Description
--------|----|-------|-----------
`$request`|`\Symfony\Component\HttpFoundation\Request`|`null`|

Return Value: [`Context`](Context.md)

### getContext()

```php
public function getContext(
    string $locale = null,
    \Frontastic\Common\AccountApiBundle\Domain\Session $session = null,
    string $host = null
): Context
```

*Get the current context using $locale and $session.*

This method is meant to be used in cases where $locale and $session are known or do not matter. If you need to
create a context for a request, please use {@see \Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService::createContextFromRequest()} instead!

Argument|Type|Default|Description
--------|----|-------|-----------
`$locale`|`string`|`null`|
`$session`|`\Frontastic\Common\AccountApiBundle\Domain\Session`|`null`|
`$host`|`string`|`null`|

Return Value: [`Context`](Context.md)

### getSession()

```php
public function getSession(
    \Symfony\Component\HttpFoundation\Request $request
): \Frontastic\Common\AccountApiBundle\Domain\Session
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$request`|`\Symfony\Component\HttpFoundation\Request`||

Return Value: `\Frontastic\Common\AccountApiBundle\Domain\Session`

