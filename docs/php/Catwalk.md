#  Catwalk

Fully Qualified: [`\Frontastic\Catwalk\Catwalk`](../../src/php/Catwalk.php)

The public entry points of this class are used to boot the Frontastic Catwalk
backend so that the entry files (index.php and console) are only very few
lines of code that dispatch here.

## Methods

* [runWeb()](#runweb)
* [runCommandline()](#runcommandline)

### runWeb()

```php
static public function runWeb(
    string $projectDirectory,
    mixed $autoloader
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$projectDirectory`|`string`||
`$autoloader`|`mixed`||

Return Value: `void`

### runCommandline()

```php
static public function runCommandline(
    string $projectDirectory,
    mixed $autoloader
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$projectDirectory`|`string`||
`$autoloader`|`mixed`||

Return Value: `void`

