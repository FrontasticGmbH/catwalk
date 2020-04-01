# `abstract`  Formatter

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\RemoteDecorator\Formatter`](../../../../../src/php/ApiCoreBundle/Domain/RemoteDecorator/Formatter.php)

## Methods

* [encode()](#encode)
* [decode()](#decode)

### encode()

```php
abstract public function encode(
    mixed $value
): string
```

*Encode object graph into a string*

Argument|Type|Default|Description
--------|----|-------|-----------
`$value`|`mixed`||

Return Value: `string`

### decode()

```php
abstract public function decode(
    string $value
): mixed
```

*Decode string into object graph*

Argument|Type|Default|Description
--------|----|-------|-----------
`$value`|`string`||

Return Value: `mixed`

