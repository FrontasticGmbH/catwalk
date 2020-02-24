#  ContentSecurityPolicy

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\EventListener\ContentSecurityPolicy`](../../../../src/php/FrontendBundle/EventListener/ContentSecurityPolicy.php)

## Methods

* [__construct()](#__construct)
* [onKernelResponse()](#onkernelresponse)

### __construct()

```php
public function __construct(
    Context $context
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||

Return Value: `mixed`

### onKernelResponse()

```php
public function onKernelResponse(
    \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$event`|`\Symfony\Component\HttpKernel\Event\FilterResponseEvent`||

Return Value: `mixed`

