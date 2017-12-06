<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);    
        $this->middleware('auth:api')->except(['index']);
        $this->middleware('scope:manage_product')->except(['index']);
        $this->middleware('can:add-category,product')->only('update');
        $this->middleware('can:delete-category,product')->only('destroy');
    }

    public function index(Product $product)
    {
        $categories = $product->categories;
        return $this->showAll($categories);
    }

    public function update(Request $request, Product $product, Category $category)
    {
        // sync: 附属上数据, 不显示,不删除重复的数据
        // syncWithoutDetaching: 附属上数据, 不显示,删除重复的数据
        // attach: 附属上数据, 显示,不删除重复的数据
        // detach: 移除附属的数据
        $product->categories()->syncWithoutDetaching([$category->id]);
        return $this->showAll($product->categories);
    }

    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->find($category->id)) {
            return $this->errorRespond('the specify category does not find', 404);
        }

        $product->categories()->detach($category->id);

        return $this->showAll($product->categories);
    }
}
