#  HooksCallBuilder

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksCallBuilder`](../../../../../src/php/ApiCoreBundle/Domain/Hooks/HooksCallBuilder.php)

## Methods

* [__construct()](#__construct)
* [name()](#name)
* [project()](#project)
* [context()](#context)
* [arguments()](#arguments)
* [header()](#header)
* [build()](#build)

### __construct()

```php
public function __construct(
    mixed $serializer
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$serializer`|`mixed`||

Return Value: `mixed`

### name()

```php
public function name(
    string $name
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$name`|`string`||

Return Value: `mixed`

### project()

```php
public function project(
    string $project
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$project`|`string`||

Return Value: `mixed`

### context()

```php
public function context(
    mixed $context
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|`mixed`||

Return Value: `mixed`

### arguments()

```php
public function arguments(
    array $arguments
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$arguments`|`array`||

Return Value: `mixed`

### header()

```php
public function header(
    string $key,
    mixed $value
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$key`|`string`||
`$value`|`mixed`||

Return Value: `mixed`

### build()

```php
public function build(): HooksCall
```

Return Value: [`HooksCall`](HooksCall.md)

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
