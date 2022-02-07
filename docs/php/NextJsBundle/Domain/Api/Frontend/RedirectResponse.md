#  RedirectResponse

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend\RedirectResponse`](../../../../../../src/php/NextJsBundle/Domain/Api/Frontend/RedirectResponse.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`statusCode` | `int` | `301` | *Yes* | 
`reason` | `string` |  | *Yes* | One of REASON_* constants
`targetType` | `string` |  | *Yes* | One of TARGET_TYPE_* constants
`target` | `string` |  | *Yes* | The target url or path

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
