<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read_general')->only(['show']);
    }
    
    public function index()
    {
        $sellers = Seller::has('products')->get();
        // return response()->json(['data'=>$sellers]);
        return $this->showAll($sellers);
    }

    public function show($id)
    {
        $seller = Seller::has('products')->findOrFail($id);
        // return response()->json(['data'=>$seller]);
        return $this->showOne($seller);
    }
}
