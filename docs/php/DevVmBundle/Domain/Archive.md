#  Archive

**Fully Qualified**: [`\Frontastic\Catwalk\DevVmBundle\Domain\Archive`](../../../../src/php/DevVmBundle/Domain/Archive.php)

## Methods

* [__destruct()](#__destruct)
* [dump()](#dump)
* [extract()](#extract)
* [createFromDirectory()](#createfromdirectory)
* [createFromBinaryData()](#createfrombinarydata)
* [createFromExistingArchive()](#createfromexistingarchive)

### __destruct()

```php
public function __destruct(): mixed
```

Return Value: `mixed`

### dump()

```php
public function dump(): string
```

Return Value: `string`

### extract()

```php
public function extract(
    string $directory
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$directory`|`string`||

Return Value: `void`

### createFromDirectory()

```php
static public function createFromDirectory(
    string $directory,
    ?string $filename = null
): Archive
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$directory`|`string`||
`$filename`|`?string`|`null`|

Return Value: [`Archive`](Archive.md)

### createFromBinaryData()

```php
static public function createFromBinaryData(
    string $blob,
    ?string $filename = null
): Archive
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$blob`|`string`||
`$filename`|`?string`|`null`|

Return Value: [`Archive`](Archive.md)

### createFromExistingArchive()

```php
static public function createFromExistingArchive(
    string $filename
): Archive
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$filename`|`string`||

Return Value: [`Archive`](Archive.md)

