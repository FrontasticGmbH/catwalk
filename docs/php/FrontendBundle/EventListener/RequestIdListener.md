#  RequestIdListener

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener`](../../../../src/php/FrontendBundle/EventListener/RequestIdListener.php)

**Implements**: `\Symfony\Component\EventDispatcher\EventSubscriberInterface`

We might expose this as an HTTP header in the future. This should probably be
moved to the index.php at some point, as soon as that's part of
`paas/catwalk`.

## Methods

* [onKernelRequest()](#onkernelrequest)
* [getSubscribedEvents()](#getsubscribedevents)

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

### getSubscribedEvents()

```php
static public function getSubscribedEvents(): mixed
```

Return Value: `mixed`

