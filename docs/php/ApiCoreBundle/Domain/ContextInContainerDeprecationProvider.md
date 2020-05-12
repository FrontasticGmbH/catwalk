#  ContextInContainerDeprecationProvider

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\ContextInContainerDeprecationProvider`](../../../../src/php/ApiCoreBundle/Domain/ContextInContainerDeprecationProvider.php)

Retrieving the Context trough the Container should not have been possible in
the first place. We removed all cases where this is required in the Catwalk
core. This service provides a legacy way to retrieve the Context from the
Container in production and throws an exception in development.

## Methods

* [__construct()](#__construct)
* [provideContext()](#providecontext)

### __construct()

```php
public function __construct(
    ContextService $contextService
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$contextService`|[`ContextService`](ContextService.md)||

Return Value: `mixed`

### provideContext()

```php
public function provideContext(): Context
```

Return Value: [`Context`](Context.md)

