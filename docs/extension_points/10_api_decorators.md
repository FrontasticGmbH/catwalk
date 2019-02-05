# Overview on API Decorators

The API decorators allow you to register a decorator to be called *before and
after* each method call to any API.

* If called before an API call you can modify the data passed to the API or
  perform additional actions.

* If called after an API you can modify or enrich the result or jsut react to
  the API response.

We provide the API decorators natively for all our APIs, this means they are
vailable right now for the following APIs:

* AccountAPI
* CartAPI
* ContentAPI
* ProductAPI
* WishlistAPI

In  the [Example: Adding Information to Products](20_product_enhancer.md) we
document the process of creating a custom decorator in more detail, but the
general scheme is described in this document.

## Process

We are using Symfony Service Container Tags to allow you to register new
decorators. Each API has their own tags and as soon as you decorator is
registered and contains methods following a certain scheme those methods will
be called. The service tags are:

* AccountAPI: `accountApi.lifecycleEventListener`
* CartAPI: `cartApi.lifecycleEventListener`
* ContentAPI: `contentApi.lifecycleEventListener`
* ProductAPI: `productApi.lifecycleEventListener`
* WishlistAPI: `wishlistApi.lifecycleEventListener`

Registering a custom decorator thus could look like:

```
    <service id="Acme\ProductBundle\Domain\AcmeDecorator">
        <!-- service arguments… -->
        
        <tag name="productApi.lifecycleEventListener" />
    </service>
```

The method naming scheme is the following: Each method from each API will be
prefixed with `before` and `after` repectively. Frontastic will check if such a
method is defined in you decorator and just call it with the aggregated API and
all original arguments for the before call and with the aggregate and the
return value for the after call.

If we take a look at `ProductApi::getProduct(ProductQuery $query):
?Product` this means you can deine th following methods in you
decorator:

```
    public function beforeGetProduct(ProductApi $api, ProductQuery $query)
    {
        // Do something before a product is retrieved, for example
        // adding additional filters to the query.
    }

    public function afterGetProduct(ProductApi $api, ?Product $result): ?Product
    {
        // Modify the returned product, for example with different prices or
        // additional data.
    }
```

We do *not* provide an interface for the decorators because we want to allow
you to define just a subset of theose methods, while an interface would force
you to implement all of them. At some point we might provide you with base
classes for all decorators which provide empty defualt implementations of all
available methods.

This works for all methods in all API interfaces.

