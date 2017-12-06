<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\Transformers\ProductTransformer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input:'.ProductTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage_product');
    }
    
    public function index(Seller $seller)
    {
        if (request()->user()->tokenCan('read_general') ||
            request()->user()->tokenCan('manage_product')
        ) {
            $products = $seller->products;
            return $this->showAll($products);
        }

        throw new AuthorizationException;
        
    }

    public function store(Request $request, Seller $seller)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ]);

        // $data['image'] = '2.jpg';
        $data['image'] = $request->image->store('');
        $data['status'] = 1;
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        return $this->showOne($product);
    }

    public function update(Request $request, Seller $seller, Product $product)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'status' => 'in:0,1',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }

        $product->fill($data);
        if ($product->isClean()) {
            return $this->errorRespond('you must specify a different value', 403);
        }
        $product->save();

        return $this->showOne($product);
    }

    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);
        $product->delete();
        Storage::delete($product->image);
        return $this->showOne($product);
    }

    protected function checkSeller(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpException("Error Processing Request", 1);
        }
    }
}
