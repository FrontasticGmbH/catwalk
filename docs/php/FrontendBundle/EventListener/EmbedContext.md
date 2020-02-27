#  EmbedContext

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\EventListener\EmbedContext`](../../../../src/php/FrontendBundle/EventListener/EmbedContext.php)

## Methods

* [__construct()](#__construct)
* [onKernelResponse()](#onkernelresponse)

### __construct()

```php
public function __construct(
    NodeExtension $nodeExtension
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$nodeExtension`|[`NodeExtension`](../Twig/NodeExtension.md)||

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

