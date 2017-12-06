<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read_general')->only(['index']);
        $this->middleware('can:view,buyer')->only(['index']);
    }

    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.category')
            ->get()
            ->pluck('product')
            ->collapse();
        return $this->showAll($categories);
    }
}
