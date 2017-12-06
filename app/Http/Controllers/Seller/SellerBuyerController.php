<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerBuyerController extends ApiController
{
    public function index(Seller $seller)
    {
        $this->forbidAdminAction();
        $buyers = $seller->products()
            ->whereHas('transactions')
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions.buyer')
            ->unique('id')
            ->values();

        return $this->showAll($buyers);
    }
}
