#  StreamService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\StreamService`](../../../../src/php/FrontendBundle/Domain/StreamService.php)

## Methods

* [__construct()](#__construct)
* [addStreamHandler()](#addstreamhandler)
* [addStreamHandlerV2()](#addstreamhandlerv2)
* [addStreamOptimizer()](#addstreamoptimizer)
* [getUsedStreams()](#getusedstreams)
* [completeDefaultStreams()](#completedefaultstreams)
* [getStreamData()](#getstreamdata)

### __construct()

```php
public function __construct(
    TasticService $tasticService,
    \Psr\Log\LoggerInterface $logger,
    \Symfony\Component\HttpFoundation\RequestStack $requestStack,
    iterable $streamHandlers = [],
    iterable $streamOptimizers = [],
    bool $debug = false
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$tasticService`|[`TasticService`](../../ApiCoreBundle/Domain/TasticService.md)||
`$logger`|`\Psr\Log\LoggerInterface`||
`$requestStack`|`\Symfony\Component\HttpFoundation\RequestStack`||
`$streamHandlers`|`iterable`|`[]`|Only "legacy" stream handlers go here, StreamHandlerV2 please go to {@link self::addStreamHandlerV2()}.
`$streamOptimizers`|`iterable`|`[]`|
`$debug`|`bool`|`false`|

Return Value: `mixed`

### addStreamHandler()

```php
public function addStreamHandler(
    StreamHandler $streamHandler
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$streamHandler`|[`StreamHandler`](StreamHandler.md)||

Return Value: `mixed`

### addStreamHandlerV2()

```php
public function addStreamHandlerV2(
    string $streamType,
    StreamHandlerV2 $streamHandler
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$streamType`|`string`||
`$streamHandler`|[`StreamHandlerV2`](StreamHandlerV2.md)||

Return Value: `void`

### addStreamOptimizer()

```php
public function addStreamOptimizer(
    StreamOptimizer $streamOptimizer
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$streamOptimizer`|[`StreamOptimizer`](StreamOptimizer.md)||

Return Value: `mixed`

### getUsedStreams()

```php
public function getUsedStreams(
    Node $node,
    Page $page,
    array &$parameterMap
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$node`|[`Node`](Node.md)||
`$page`|[`Page`](Page.md)||
`&$parameterMap`|`array`||

Return Value: `array`

### completeDefaultStreams()

```php
public function completeDefaultStreams(
    Node $node,
    Page $page
): Page
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$node`|[`Node`](Node.md)||
`$page`|[`Page`](Page.md)||

Return Value: [`Page`](Page.md)

### getStreamData()

```php
public function getStreamData(
    Node $node,
    Context $context,
    array $parameterMap = [],
    Page $page = null
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$node`|[`Node`](Node.md)||
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||
`$parameterMap`|`array`|`[]`|Mapping stream IDs to parameter arrays
`$page`|[`Page`](Page.md)|`null`|

Return Value: `array`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
