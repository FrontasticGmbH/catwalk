#  NodeExtension

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Twig\NodeExtension`](../../../../src/php/FrontendBundle/Twig/NodeExtension.php)

**Extends**: `\Twig_Extension`

**Implements**: `\Twig_Extension_GlobalsInterface`

## Methods

* [__construct()](#__construct)
* [getFunctions()](#getfunctions)
* [getGlobals()](#getglobals)
* [completeInformation()](#completeinformation)

### __construct()

```php
public function __construct(
    \Symfony\Component\DependencyInjection\ContainerInterface $container,
    \Psr\SimpleCache\CacheInterface $cache
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$container`|`\Symfony\Component\DependencyInjection\ContainerInterface`||
`$cache`|`\Psr\SimpleCache\CacheInterface`||

Return Value: `mixed`

### getFunctions()

```php
public function getFunctions(): array
```

Return Value: `array`

### getGlobals()

```php
public function getGlobals(): mixed
```

Return Value: `mixed`

### completeInformation()

```php
public function completeInformation(
    array $variables
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$variables`|`array`||

Return Value: `array`

