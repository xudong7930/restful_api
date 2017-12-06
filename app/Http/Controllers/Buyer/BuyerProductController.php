<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Buyer;

class BuyerProductController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read_general')->only(['index']);
        $this->middleware('can:view,buyer')->only(['index']);
    }
    
    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()
            ->with('product')
            ->get()
            ->pluck('product');
        return $this->showAll($products);
    }
}
