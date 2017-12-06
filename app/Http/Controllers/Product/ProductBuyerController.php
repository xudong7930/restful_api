<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Product;
use App\Http\Controllers\ApiController;

class ProductBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Product $product)
    {
        $buyers = $product->transactions
            ->whereHas('buyer')
            ->with('buyer')
            ->get()
            ->pluck('buyer')
            ->unique('id')
            ->values();
        return $this->showAll($buyers);
    }
}
