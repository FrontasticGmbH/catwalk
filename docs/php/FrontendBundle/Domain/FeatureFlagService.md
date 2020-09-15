#  FeatureFlagService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\FeatureFlagService`](../../../../src/php/FrontendBundle/Domain/FeatureFlagService.php)

**Implements**: [`ContextDecorator`](../../ApiCoreBundle/Domain/ContextDecorator.md)

## Methods

* [__construct()](#__construct)
* [decorate()](#decorate)

### __construct()

```php
public function __construct(
    DataRepository $repository = null
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$repository`|[`DataRepository`](../../ApiCoreBundle/Domain/DataRepository.md)|`null`|

Return Value: `mixed`

### decorate()

```php
public function decorate(
    Context $context
): Context
```

** @SuppressWarnings(PHPMD.CyclomaticComplexity)*

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||

Return Value: [`Context`](../../ApiCoreBundle/Domain/Context.md)

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
