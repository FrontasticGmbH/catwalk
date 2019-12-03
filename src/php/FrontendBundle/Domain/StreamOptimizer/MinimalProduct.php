<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamOptimizer;

use Frontastic\Catwalk\FrontendBundle\Domain\StreamOptimizer;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamContext;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;

use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\Variant;

class MinimalProduct implements StreamOptimizer
{
    /**
     * Attributes to keep in variant
     *
     * @var array
     */
    private $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = array_flip($attributes);
    }

    /**
     * @return mixed
     */
    public function optimizeStreamData(Stream $stream, StreamContext $streamContext, $data)
    {
        if ($stream->type !== 'product-list') {
            return $data;
        }

        foreach ($data->items as $index => $product) {
            $data->items[$index] = new Product([
                'productId' => $product->productId,
                'slug' => $product->slug,
                'name' => $product->name,
                'variants' => [
                    new Variant([
                        'sku' => $product->variants[0]->sku,
                        'price' => $product->variants[0]->price,
                        'images' => $product->variants[0]->images,
                        'attributes' => array_intersect_key(
                            $product->variants[0]->attributes,
                            $this->attributes
                        ),
                    ])
                ]
            ]);
        }

        return $data;
    }
}
