<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'id' => (int)$seller->id,
            'name' => (string)$seller->name,
            'email' => (string)$seller->email,
            'verified' => (bool)$seller->verified,
            'createAt' => (string)$seller->created_at,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('sellers.show', $seller->id)
                ],
                [
                    'rel' => 'sellers.category',
                    'href' => route('sellers.category.index', $seller->id)
                ],
                [
                    'rel' => 'sellers.products',
                    'href' => route('sellers.products.index', $seller->id),
                ],
                [
                    'rel' => 'sellers.buyers',
                    'href' => route('sellers.buyer.index', $seller->id),
                ],
                [
                    'rel' => 'sellers.transactions',
                    'href' => route('sellers.transaction.index', $seller->id),
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $seller->id)
                ]
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'verified' => 'verified',
            'createAt' => 'created_at',
        ];
        return $attributes[$index] ?: null;
    }

    public static function transformAttribute($index)
    {
        $attributes = [
            'id' => 'id',
            'name' => 'name',
            'email' => 'email',
            'verified' => 'verified',
            'createAt' => 'created_at',
        ];
        return $attributes[$index] ?: null;
    }
}
