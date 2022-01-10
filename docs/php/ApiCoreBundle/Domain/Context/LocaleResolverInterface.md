# `interface`  LocaleResolverInterface

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\Context\LocaleResolverInterface`](../../../../../src/php/ApiCoreBundle/Domain/Context/LocaleResolverInterface.php)

## Methods

* [determineLocale()](#determinelocale)

### determineLocale()

```php
public function determineLocale(
    \Symfony\Component\HttpFoundation\Request $request,
    \Frontastic\Common\ReplicatorBundle\Domain\Project $project
): string
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$request`|`\Symfony\Component\HttpFoundation\Request`||
`$project`|`\Frontastic\Common\ReplicatorBundle\Domain\Project`||

Return Value: `string`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
