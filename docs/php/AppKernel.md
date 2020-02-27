#  AppKernel

**Fully Qualified**: [`\Frontastic\Catwalk\AppKernel`](../../src/php/AppKernel.php)

**Extends**: `\Frontastic\Common\Kernel`

Property|Type|Default|Description
--------|----|-------|-----------
`static` `catwalkBaseDir`|``||

## Methods

* [registerBundles()](#registerbundles)
* [registerContainerConfiguration()](#registercontainerconfiguration)
* [getProjectDir()](#getprojectdir)
* [getRootDir()](#getrootdir)
* [getAdditionalConfigFiles()](#getadditionalconfigfiles)
* [getBaseDir()](#getbasedir)

### registerBundles()

```php
public function registerBundles(): mixed
```

Return Value: `mixed`

### registerContainerConfiguration()

```php
public function registerContainerConfiguration(
    \Symfony\Component\Config\Loader\LoaderInterface $loader
): mixed
```

*Register symfony configuration from base dir.*

Argument|Type|Default|Description
--------|----|-------|-----------
`$loader`|`\Symfony\Component\Config\Loader\LoaderInterface`||

Return Value: `mixed`

### getProjectDir()

```php
public function getProjectDir(): mixed
```

*Symfony uses reflection and AppKernel class file otherwise.*

Return Value: `mixed`

### getRootDir()

```php
public function getRootDir(): mixed
```

*Symfony uses reflection and AppKernel class file otherwise.*

Return Value: `mixed`

### getAdditionalConfigFiles()

```php
static public function getAdditionalConfigFiles(): mixed
```

Return Value: `mixed`

### getBaseDir()

```php
static public function getBaseDir(): string
```

Return Value: `string`

