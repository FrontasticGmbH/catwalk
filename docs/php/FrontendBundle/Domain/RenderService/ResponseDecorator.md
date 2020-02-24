#  ResponseDecorator

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\Domain\RenderService\ResponseDecorator`](../../../../../src/php/FrontendBundle/Domain/RenderService/ResponseDecorator.php)

## Methods

* [setTimedOut()](#settimedout)
* [onKernelException()](#onkernelexception)
* [onKernelResponse()](#onkernelresponse)

### setTimedOut()

```php
public function setTimedOut(): mixed
```

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

