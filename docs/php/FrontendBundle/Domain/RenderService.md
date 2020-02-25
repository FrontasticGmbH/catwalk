#  RenderService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\RenderService`](../../../../src/php/FrontendBundle/Domain/RenderService.php)

## Methods

* [__construct()](#__construct)
* [render()](#render)

### __construct()

```php
public function __construct(
    ContextService $contextService,
    ResponseDecorator $responseDecorator,
    \Frontastic\Common\HttpClient $httpClient,
    string $backendUrl,
    \Frontastic\Common\JsonSerializer $jsonSerializer,
    \Psr\Log\LoggerInterface $logger
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$contextService`|[`ContextService`](../../ApiCoreBundle/Domain/ContextService.md)||
`$responseDecorator`|[`ResponseDecorator`](RenderService/ResponseDecorator.md)||
`$httpClient`|`\Frontastic\Common\HttpClient`||
`$backendUrl`|`string`||
`$jsonSerializer`|`\Frontastic\Common\JsonSerializer`||
`$logger`|`\Psr\Log\LoggerInterface`||

Return Value: `mixed`

### render()

```php
public function render(
    \Symfony\Component\HttpFoundation\Request $request,
    array $props = []
): \Frontastic\Common\HttpClient\Response
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$request`|`\Symfony\Component\HttpFoundation\Request`||
`$props`|`array`|`[]`|

Return Value: `\Frontastic\Common\HttpClient\Response`

