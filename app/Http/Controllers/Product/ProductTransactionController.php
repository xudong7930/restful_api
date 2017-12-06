<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Product;
use App\Http\Controllers\ApiController;

class ProductTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Product $product)
    {
        $transactions = $product->transactions
            ->get();
        return $this->showAll($transactions);
    }
}
