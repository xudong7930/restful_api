<?php

namespace App;

use App\Acme\Scopes\SellerScope;
use App\Product;
use App\Transformers\SellerTransformer;
use App\User;

class Seller extends User
{
    public $transformer = SellerTransformer::class;

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
