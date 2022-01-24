#  FrontendContext

**Fully Qualified**: [`\Frontastic\Catwalk\NextJsBundle\Domain\Api\FrontendContext`](../../../../../src/php/NextJsBundle/Domain/Api/FrontendContext.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

In contrast to the regular Context, this does not include sensitive
configuration data.
Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`locales` | `string[]` | `[]` | - | All available locales in form `<language>_<territory>` or just `<territory>`.
`defaultLocale` | `string` |  | - | Locale to fall back to when no fitting locale could be determined.
`environment` | `string` | `'production'` | - | One of "development", "staging" or "production".

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
