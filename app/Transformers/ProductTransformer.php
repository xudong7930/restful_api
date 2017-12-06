<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{

    public function transform(Product $product)
    {
        return [
            'id' => (int)$product->id,
            'name' => (string)$product->name,
            'detail' => (string)$product->description,
            'quantity' => (int)$product->quantity,
            'seller' => (int)$product->seller_id,
            'image' => (string)$product->image,
            'status' => (int)$product->status,
            'createAt' => (string)$product->created_at,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $product->id)
                ],
                [
                    'rel' => 'products.buyers',
                    'href' => route('products.buyers.index', $product->id)
                ],
                [
                    'rel' => 'products.categories',
                    'href' => route('products.categories.index', $product->id)
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show', $product->seller_id)
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'detail' => 'detail',
            'quantity' => 'quantity',
            'seller' => 'seller_id',
            'image' => 'image',
            'status' => 'status',
            'createAt' => 'created_at',
        ];
        return $attributes[$index] ?: null;
    }

    public static function transformAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'detail' => 'detail',
            'quantity' => 'quantity',
            'seller_id' => 'seller',
            'image' => 'image',
            'status' => 'status',
            'created_at' => 'createAt'
        ];
        return $attributes[$index] ?: null;
    }
}
