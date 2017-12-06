<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Transaction;
use App\User;
use DB;
use Illuminate\Http\Request;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:purchase_product')->only(['store']);

        // purchase: 方法名称,buyer: model类
        $this->middleware('can:purchase,buyer')->only(['index']);
    }
    
    public function store(Request $request, Product $product, User $buyer)
    {
        $data = $this->validate($request, [
            'quantity' => 'required|integer|min:1'
        ]);

        if ($buyer->id == $product->seller_id) {
            return $this->errorRespond('buyer can not be the seller', 409);
        }

        if (!$buyer->isVerified()) {
            return $this->errorRespond('buyer must be verified', 409);   
        }

        if (!$product->seller->isVerified()) {
            return $this->errorRespond('seller must be verified', 409);
        }

        if (!$product->isAvailable()) {
            return $this->errorRespond('product must be available', 409);
        }

        if ($product->quantity < $request->quantity) {
            return $this->errorRespond('does not have enough product', 409);
        }

        $data['buyer_id'] = $buyer->id;
        $data['product_id'] = $product->id;

        return DB::transaction(function () use ($request, $product, $buyer, $data) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create($data);
            return $this->showOne($transaction);
        });
    }
}
