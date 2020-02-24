#  MissingHomepageRouteListener

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\EventListener\MissingHomepageRouteListener`](../../../../src/php/FrontendBundle/EventListener/MissingHomepageRouteListener.php)

## Methods

* [__construct()](#__construct)
* [onKernelException()](#onkernelexception)

### __construct()

```php
public function __construct(
    \Symfony\Component\Templating\EngineInterface $templating,
    bool $debug = false
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$templating`|`\Symfony\Component\Templating\EngineInterface`||
`$debug`|`bool`|`false`|

Return Value: `mixed`

### onKernelException()

```php
public function onKernelException(
    \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$event`|`\Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent`||

Return Value: `mixed`

