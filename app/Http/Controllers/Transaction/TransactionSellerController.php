<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read_general')->only(['index']);
        $this->middleware('can:view,transaction')->only('index');
    }
    
    public function index(Transaction $transaction)
    {
        $sellers = $transaction->product->sellers;
    
        return $this->showOne($sellers);
    }
}
