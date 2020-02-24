#  RedirectListener

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\EventListener\RedirectListener`](../../../../src/php/FrontendBundle/EventListener/RedirectListener.php)

## Methods

* [__construct()](#__construct)
* [onKernelException()](#onkernelexception)

### __construct()

```php
public function __construct(
    RedirectService $redirectService
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$redirectService`|[`RedirectService`](../Domain/RedirectService.md)||

Return Value: `mixed`

### onKernelException()

```php
public function onKernelException(
    \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$event`|`\Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent`||

Return Value: `void`

