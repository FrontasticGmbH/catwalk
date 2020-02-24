#  DataRepository

Fully Qualified: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\DataRepository`](../../../../src/php/ApiCoreBundle/Domain/DataRepository.php)

## Methods

* [store()](#store)
* [remove()](#remove)
* [findOneByEvenIfDeleted()](#findonebyevenifdeleted)

### store()

```php
public function store(
    \Kore\DataObject\DataObject $data
): \Kore\DataObject\DataObject
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$data`|`\Kore\DataObject\DataObject`||

Return Value: `\Kore\DataObject\DataObject`

### remove()

```php
public function remove(
    \Kore\DataObject\DataObject $data
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$data`|`\Kore\DataObject\DataObject`||

Return Value: `mixed`

### findOneByEvenIfDeleted()

```php
public function findOneByEvenIfDeleted(
    array $criteria,
    array $orderBy = null
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$criteria`|`array`||
`$orderBy`|`array`|`null`|

Return Value: `mixed`

