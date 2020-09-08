#  ClientFactory

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\CommerceTools\ClientFactory`](../../../../../src/php/ApiCoreBundle/Domain/CommerceTools/ClientFactory.php)

## Methods

* [__construct()](#__construct)
* [factorForConfigurationSection()](#factorforconfigurationsection)

### __construct()

```php
public function __construct(
    \Frontastic\Common\ReplicatorBundle\Domain\Project $project,
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Commercetools\ClientFactory $projectClientFactory
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$project`|`\Frontastic\Common\ReplicatorBundle\Domain\Project`||
`$projectClientFactory`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Commercetools\ClientFactory`||

Return Value: `mixed`

### factorForConfigurationSection()

```php
public function factorForConfigurationSection(
    string $configurationSectionName
): \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Commercetools\Client
```

*Creates a CommerceTools Client for the given configuration section.*

Example could be $factory->factorForConfigurationSection("product"), if
you want to use it in a product related call.  See project.yml for
possible configuration section names.

Argument|Type|Default|Description
--------|----|-------|-----------
`$configurationSectionName`|`string`||

Return Value: `\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Commercetools\Client`

