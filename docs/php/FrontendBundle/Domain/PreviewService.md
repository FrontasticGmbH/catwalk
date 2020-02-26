#  PreviewService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\PreviewService`](../../../../src/php/FrontendBundle/Domain/PreviewService.php)

## Methods

* [__construct()](#__construct)
* [get()](#get)
* [store()](#store)
* [remove()](#remove)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\PreviewGateway $previewGateway
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$previewGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\PreviewGateway`||

Return Value: `mixed`

### get()

```php
public function get(
    string $previewId
): Preview
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$previewId`|`string`||

Return Value: [`Preview`](Preview.md)

### store()

```php
public function store(
    Preview $preview
): Preview
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$preview`|[`Preview`](Preview.md)||

Return Value: [`Preview`](Preview.md)

### remove()

```php
public function remove(
    Preview $preview
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$preview`|[`Preview`](Preview.md)||

Return Value: `void`

