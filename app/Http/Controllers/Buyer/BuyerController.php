<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read_general')->only(['index']);
        $this->middleware('can:view,buyer')->only(['show']);
    }
    
    public function index()
    {
        $buyers = Buyer::has('transactions')->get();
        // return response()->json(['data'=>$buyers]);
        return $this->showAll($buyers);
    }

    public function show($id)
    {
        $buyer = Buyer::has('transactions')->findOrFail($id);
        // return response()->json(['data'=>$buyer]);
        return $this->showOne($buyer);
    }
}
