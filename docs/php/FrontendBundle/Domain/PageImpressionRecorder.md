#  PageImpressionRecorder

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\PageImpressionRecorder`](../../../../src/php/FrontendBundle/Domain/PageImpressionRecorder.php)

## Methods

* [__construct()](#__construct)
* [onKernelTerminate()](#onkernelterminate)

### __construct()

```php
public function __construct(
    \Domnikl\Statsd\Client $statsd
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$statsd`|`\Domnikl\Statsd\Client`||

Return Value: `mixed`

### onKernelTerminate()

```php
public function onKernelTerminate(
    \Symfony\Component\HttpKernel\Event\PostResponseEvent $event
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$event`|`\Symfony\Component\HttpKernel\Event\PostResponseEvent`||

Return Value: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
