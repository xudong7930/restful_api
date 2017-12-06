<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryProductController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']);    
        $this->middleware('auth:api')->except(['index', 'show']);    
    }

    public function index(Category $category)
    {
        $categories = $category->products;
        return $this->showAll($categories);
    }
}
