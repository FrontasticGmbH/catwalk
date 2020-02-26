#  RebuildRoutesListener

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\EventListener\RebuildRoutesListener`](../../../../src/php/FrontendBundle/EventListener/RebuildRoutesListener.php)

## Methods

* [__construct()](#__construct)
* [onKernelRequest()](#onkernelrequest)

### __construct()

```php
public function __construct(
    \Symfony\Component\DependencyInjection\ContainerInterface $container,
    \Psr\Log\LoggerInterface $logger
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$container`|`\Symfony\Component\DependencyInjection\ContainerInterface`||
`$logger`|`\Psr\Log\LoggerInterface`||

Return Value: `mixed`

### onKernelRequest()

```php
public function onKernelRequest(
    \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$event`|`\Symfony\Component\HttpKernel\Event\GetResponseEvent`||

Return Value: `mixed`

