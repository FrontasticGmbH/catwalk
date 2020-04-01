#  Endpoint

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\RemoteDecorator\Endpoint`](../../../../../src/php/ApiCoreBundle/Domain/RemoteDecorator/Endpoint.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Description
--------|----|-------|-----------
`url`|`string`||
`format`|`string`|`'json'`|
`method`|`string`|`'POST'`|
`timeout`|`float`|`0.2`|Allowed time for requests in seconds
`optional`|`bool`|`false`|If the endpoint fails to respond, shall the stream error (default) or should we respond with the original value.
`headers`|`string[]`|`['Content-Type: application/json', 'Accept: application/json']`|

