# `interface`  StreamOptimizer

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\Domain\StreamOptimizer`](../../../../src/php/FrontendBundle/Domain/StreamOptimizer.php)

See the documentation for example and details.

## Methods

* [optimizeStreamData()](#optimizestreamdata)

### optimizeStreamData()

```php
public function optimizeStreamData(
    Stream $stream,
    StreamContext $streamContext,
    mixed $data
): mixed
```

*Optimize the given stream $data and return the optimized version.*

The information given in $stream and $streamContext refers to the current usage and
can deal for optimizing a stream for a dedicated use-case.

Argument|Type|Default|Description
--------|----|-------|-----------
`$stream`|[`Stream`](Stream.md)||
`$streamContext`|[`StreamContext`](StreamContext.md)||
`$data`|`mixed`||

Return Value: `mixed`

