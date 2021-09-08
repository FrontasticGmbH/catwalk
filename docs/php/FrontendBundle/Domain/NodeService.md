#  NodeService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\NodeService`](../../../../src/php/FrontendBundle/Domain/NodeService.php)

**Implements**: `\Frontastic\Common\ReplicatorBundle\Domain\Target`

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)
* [fill()](#fill)
* [getNodes()](#getnodes)
* [getTree()](#gettree)
* [get()](#get)
* [store()](#store)
* [remove()](#remove)
* [completeCustomNodeData()](#completecustomnodedata)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\NodeGateway $nodeGateway,
    RouteService $routeService,
    SchemaService $schemaService
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$nodeGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\NodeGateway`||
`$routeService`|[`RouteService`](RouteService.md)||
`$schemaService`|[`SchemaService`](SchemaService.md)||

Return Value: `mixed`

### lastUpdate()

```php
public function lastUpdate(): string
```

Return Value: `string`

### replicate()

```php
public function replicate(
    array $updates
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$updates`|`array`||

Return Value: `void`

### fill()

```php
public function fill(
    Node $node,
    array $data
): Node
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$node`|[`Node`](Node.md)||
`$data`|`array`||

Return Value: [`Node`](Node.md)

### getNodes()

```php
public function getNodes(
    string $root = null,
    int $maxDepth = null
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$root`|`string`|`null`|
`$maxDepth`|`int`|`null`|

Return Value: `array`

### getTree()

```php
public function getTree(
    string $root = null,
    int $maxDepth = null
): Node
```

*Returns a (cloned) tree of nodes.*

Note that changes to the returned nodes are *not* reflected to other instances of the same node. We need to
reflect that multiple trees with different depth might be fetched in the same request.

Argument|Type|Default|Description
--------|----|-------|-----------
`$root`|`string`|`null`|
`$maxDepth`|`int`|`null`|

Return Value: [`Node`](Node.md)

### get()

```php
public function get(
    string $nodeId
): Node
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$nodeId`|`string`||

Return Value: [`Node`](Node.md)

### store()

```php
public function store(
    Node $node
): Node
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$node`|[`Node`](Node.md)||

Return Value: [`Node`](Node.md)

### remove()

```php
public function remove(
    Node $node
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$node`|[`Node`](Node.md)||

Return Value: `void`

### completeCustomNodeData()

```php
public function completeCustomNodeData(
    Node $node,
    ?\Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor $fieldVisitor = null
): Node
```

*TODO: Call this automatically when a node is loaded to have Frontastic React benefit from it, too!*

Argument|Type|Default|Description
--------|----|-------|-----------
`$node`|[`Node`](Node.md)||
`$fieldVisitor`|`?\Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor`|`null`|

Return Value: [`Node`](Node.md)

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
