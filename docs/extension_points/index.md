# Extension Points

We offer different extension points at various different layers. Since the work
on Frontastic is still very much in progress those extension points have
different API stabilities.

Our main integration in the backend layer are decorators for all the API
integrations we provide. An example for such a decorator would be adding
additional information to products when they are loaded, perform price
calculations or verify and modify what is put into the cart, for example to
calculate custom discounts not supported by your commerce backend.

In the frontend layer you will primarily write new tastics and patterns, which
is documented elsewhere, but you can also hook into our redux stack by writing
your own reducers and action producers.

1. [Overview on API Decorators](10_api_decorators.md)
2. [Example: Adding Information to Products](20_product_enhancer.md)
