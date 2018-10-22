# Implementing a Simple Tastic

In this tutorial we implement a simple Tastic that displays a list of products.
We use the `ProductList` Tastic that is shipped with Frontastic as the basis
but stripped the HTML code down a bit for better illustration. If you want to
follow this tutorial in a copy & adapt way, please don't hesitate to copy the
following files as a starting point:

- `catwalk/src/js/tastic/productList/tastic.json`
- `catwalk/src/js/tastic/productList/tastic.jsx`

## File Placement

Custom Tastics need to be placed in the project directory they belong to. By
convention the path `<project>/src/js/tastic/` holds all custom Tastics. Each
Tastic requires its own sub-directory which is the *camelCased* tastic name.
The `tastic.json` and `tastic.jsx` files must be named exactly like this in the
Tastic directory.

So if you start with the example from above and have a Project named
`fall2018`, you should copy the files to:

- `fall2018/src/js/tastic/myProductList/tastic.json`
- `fall2018/src/js/tastic/myProductList/tastic.jsx`

Note the Tastic identifier change to `myProductList` to avoid clashes with the
original Tastics!

## Tastic Specification

Backstage needs to know how the Tastic requires to be initialized and what
parameters it expects to be available. This comes in form of a *JSON* file
which follows a JSON schema that can be found in: `catwalk/vendor/frontastic/common/src/php/SpecificationBundle/Resources/tasticSchema.json`.

On top level, this file contains meta information about the Tastic:

```js
{
    "tasticType": "productList",
    "name": "Product List",
    "icon": "list",
    "schema": [
        ...
    ]
}

```

The `tasticType` defines a name how the Tastic can be looked up in code. This
**needs** to be the same identifier as used for the Tastic directory above! So,
if you are working with the example, please name it `myProductList`.

The `name` provides a readable name, in contrast to that. You should provide an
`icon` for your Tastic which can be selected from the [Material Design
Icons](https://material.io/tools/icons/). The `schema` key  then contains the
parameter list for the Tastic:

```js
{
    ...
    "schema": [
        {
            "name": "Stream Selection",
            "fields": [
                {
                    "label": "Stream",
                    "field": "stream",
                    "type": "stream",
                    "streamType": "product-list",
                    "default": null
                },
                ...
                {
                    "label": "Show strike price",
                    "field": "showStrikePrice",
                    "type": "boolean",
                    "default": true
                }
            ]
        }
    ]
}
```

The schema is presented to the front-end manager as a configuration dialog. For
this reason, you structure your schema into meaningful blocks that encapsulate
a collection of semantically related fields (here, the block is named `Stream
Selection`).

Each field specifies 1 parameter that will be provided to your tastic under the
name provided as `field`. The `label` is used in the UI for the shop manager
(e.g. `Stream`). The `type` determines what kind of information you require.
Frontastic supports typical programming data types like `string`, `integer` or
`boolean`. But also advanced types like `node`, `tree`, `media` or `group`.

The latter types provide advanced UI elements for the shop manager and
resulting objcts to submit the information to your Tastic. For example a
`media` field provides image selection from the media library + configuration
for cropping and more.

@TODO: Doc & link to more advanced schema configuration.

In the shown case, the ProductList Tastic requires a `stream` of type
`product-list` and a `boolean` flag which determines if strike prices are
shown.

The specification for your Tastic should be stored in the source code
repository together with the Tastic code. To make the Tastic known by Backstage
you need to upload the schema to the Development App in Backstage once. In
addition you need to re-upload the Specification every time you change the
required parameters or any meta data.

@TODO: BC & FC constraints on Tastic specifications.

## Tastic React Code

The Tastic itself is basically a ReactJS component which receives some
pre-defined props from Catwalk. If you are not familiar with ReactJS, component
and props, yet, please refer to the
[React.Component](https://reactjs.org/docs/react-component.html) documentation
first.

The stub of the `tastic.jsx` file looks about like this:

```js
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

...

class ProductListTastic extends Component {
    render () {
        // ...
    }
}

ProductListTastic.propTypes = {
    data: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

ProductListTastic.defaultProps = {
}

export default ProductListTastic

```

While the remaining boiler plate is pretty standard for a React component, the
important part here is the `ProductListTastic.propTypes` declaration.

Every Tastic receives the prop `tastic` which contains all information that is
available about the tastic itself. This includes the `schema` (remember [A
Tastic Specification]).

The `data` prop contains all collected data the tastic required, if this data
is available yet. You can directly access the values by their name. If your
specification schema contains a field `campaignProductStream` you can access
the corresponding stream via `this.props.data.campaignProductStream`.

How this works can be seen in the following example which reveals the first
part of the `render()` method:

```js
class ProductListTastic extends Component {
    render () {
        let productList = this.props.data.stream
        if (!productList) {
            return null
        }

        let showPercent = this.props.data.showPercent || true
        let showStrikePrice = this.props.data.showStrikePrice || true

        // ...
    }
}

```

**Note** that you cannot be sure that `data` is filled at every time. For
example during an update of stream filters or view change it might be empty.
Therefore the code needs to take care for resilience and simply returns `null`
if it misses data (no rendering at all). If you can display something
meaningful instead, feel free to do that!

For convenience the two other settings (of which we saw one in the
speficication) are extracted and filled with defaults for resilience. The
remaining code in `render()` is again straight forward React:

```js
class ProductListTastic extends Component {
    render () {
        // ...

        return (<div className='o-layout'>
            {_.map(productList.items, (product) => {
                return (<div key={product.productId} className='...'>
                    <Product
                        product={product}
                        showPercent={showPercent}
                        showStrikePrice={showStrikePrice}
                    />
                </div>)
            })}
        </div>)
    }
}
```

The component wraps all HTML into a div with `o-layout` class, iterates all
items from the `product-list` stream and presents the product itself using
another React component: `<Product>`. The latter one is not a Tastic, but a
simple React component like you maybe know it from other projects.

@TODO: CSS-Framework and how to use it.

## Register the Tastic

By now you still need to register your Tastic in the code base of Catwalk. Do
this by editing `<project>/src/js/tastic/tastics.js`, import the ReactJS class of
your Tastic and add it to the list that maps tastic types to classes, e.g.

```js
// ...
import ProductList from './productList/tastic.jsx'
// ...

export default (() => {
    return {
        // ...
        'productList': ProductList,
        // ...
    }
})()

```

## Testing the Tastic

Now that you implemented a very first Tastic, it's time to preview it in your
VM. Learn how to do that in the next tutorial: [Testing a
Tastic](20_testing_tastic.md).

[A Tastic Specification]: #tastic-specification
