<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']);    
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('scope:manage_product');
    }

    public function index()
    {
        $products = Product::all();
        return $this->showAll($products);    
    }

    public function show(Product $product)
    {
        return $this->showOne($product);
    }
}
