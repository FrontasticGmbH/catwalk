# Implementing Customm Field Types

!!! stability experimental

Standard data sources in Frontastic are encapsulated in so-called *Streams*.
`product-list` and `content` are examples for these. If a Tastic defines to
retrieve a stream of a certain type it will be provided with the corresponding
data automatically at rendering time. You can extend this mechanism with your
own stream types to provide data to Tastics in a similar way.

For providing a custom stream type you need to register a stream handler in the
Frontastic PHP stack. This is shown in the following.

## Implementation

In order to handle a custom stream you need to provide an implementation of
`Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler` which is the
generalized interface for handling all field types and contains 2 methods:

```php
namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

abstract class TasticFieldHandler
{
    /**
     * @return string
     */
    abstract public function getType(): string;

    abstract public function handle(Context $context, $fieldValue);
}
```

The `getType()` method is supposed to return the type identifier for your
stream type. You *must* prefix custom stream types with your project ID in the
form`<project-id>-<stream-type>`, for example `awesomeshop-stores` if your
project is `awesomeshop` and your stream will provide information about local
stores.

The second method `handle()` performs the actual work of handling a stream of
your type. It is called by Frontastic as soon as a Tastic requires a stream of
your type.

As parameters it receives the current application `Context` and the
`$fieldValue` which was assigned by the configuring user in Backstage.

!!! note
    We are still working on this feature. Therefore it is not yet possible for
    users in backstage to assign values to custom stream fields. But even
    without this possibility custom streams can be useful to provide data to a
    Tastic.

The `handle()` method is supposed to return the value of the stream that will
then be given to the Tastic that required it. It can be an arbitrary value type
except for 1 constraint: It *must* be serializable to JSON (that excludes e.g.
`resource` types). Ideally you will return either

- a simple data object,
- an array of scalar values of simple array objects,
- null,

or a combination of these.

## Registering a Stream Handler

To register your custom stream handler you just need to make it known in the
Symfony service container and apply the tag `frontend.tasticFieldHandler` to it:

```xml
<service id="Frontastic\Customer\StorefinderBundle\Domain\StreamHandler">
    <argument type="service" id="Frontastic\Customer\StorefinderBundle\Domain\StorefinderService" />

     <tag name="frontend.tasticFieldHandler"/>
</service>
```

In this example a stream is registered for implementing a store finder. The
field handler implementation itself is rather slim and dispatches all work to a
service. This gains you the possibility to unleash the full power of
Frontastic, Symfony and your own micro-service APIs within a field handler.

## Using the Stream in a Tastic

To use the custom stream type in a Tastic you need to set the type of a field
to `stream` and set its stream type to the type identifier you selected, for
example:

```js
{
    "tasticType": "awesomeshop-store-finder",
    "name": "Storefinder",
    "category": "Awesome Shop",
    "icon": "store",
    "schema": [
        {
            "name": "General",
            "fields": [
                {
                    "label": "Stores",
                    "field": "stream",
                    "type": "stream",
                    "streamType": "awesomeshop-stores",
                    "default": null,
                    "required": false
                },
        }
    ]
}
```

## Accessing the Request

!!! warning
    Accessing the request from a Service is generally discouraged. However,
    before Frontastic offers a better way to accessing such information it can
    be used as a work-around.

Since a field handler is just a normal Symfony service you can get any service
injected to dispatch work to. This also holds for the `RequestStack` which
allows you to get hands on the currently processed request to access URL
parameters and much more:

```php
namespace Frontastic\Customer\StorefinderBundle\Domain;

use Symfony\Component\HttpFoundation\RequestStack;

use Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\ApiCoreBundle\Domain\DataRepository;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Locale;

class StoresFieldHandler extends TasticFieldHandler
{
    private $service;

    private $requestStack;

    public function __construct(StorefinderService $service, RequestStack $requestStack)
    {
        $this->service = $service;
        $this->requestStack = $requestStack;
    }

    public function getType(): string
    {
        return 'awesomeshop-stores';
    }

    public function handle(Context $context, $fieldValue)
    {
        $locationId = $this->requestStack->getCurrentRequest()->get('location', null);
        if (!$locationId) {
            return null;
        }

        $locale = Locale::createFromPosix($context->locale);
        return $this->service->getStores($locale->territory, $locationId);
    }
}
```

In this example the `RequestStack` is asked for the current request to fetch
the URL parameter `location`. If this is not set, the stream simply contains
nothing. Otherwise the corresponding domain service is used to determine stores
for the stream.
