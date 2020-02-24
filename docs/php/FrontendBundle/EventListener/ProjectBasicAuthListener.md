#  ProjectBasicAuthListener

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\EventListener\ProjectBasicAuthListener`](../../../../src/php/FrontendBundle/EventListener/ProjectBasicAuthListener.php)

## Methods

* [__construct()](#__construct)
* [onKernelRequest()](#onkernelrequest)

### __construct()

```php
public function __construct(
    ContextService $contextService
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$contextService`|[`ContextService`](../../ApiCoreBundle/Domain/ContextService.md)||

Return Value: `mixed`

### onKernelRequest()

```php
public function onKernelRequest(
    \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$event`|`\Symfony\Component\HttpKernel\Event\GetResponseEvent`||

Return Value: `void`

