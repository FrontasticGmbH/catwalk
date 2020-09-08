#  ResponseDecorator

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\RenderService\ResponseDecorator`](../../../../../src/php/FrontendBundle/Domain/RenderService/ResponseDecorator.php)

This is important for SEO, otherwise the pages without SSR might end up in
search engine indexes.

## Methods

* [setTimedOut()](#settimedout)
* [onKernelResponse()](#onkernelresponse)

### setTimedOut()

```php
public function setTimedOut(): mixed
```

Return Value: `mixed`

### onKernelResponse()

```php
public function onKernelResponse(
    \Symfony\Component\HttpKernel\Event\ResponseEvent $event
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$event`|`\Symfony\Component\HttpKernel\Event\ResponseEvent`||

Return Value: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
