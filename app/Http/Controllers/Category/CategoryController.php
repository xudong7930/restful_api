<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']);
        $this->middleware('auth:api')->except(['index', 'show']);
    }


    public function index()
    {
        $categories = Category::all();
        return $this->showAll($categories);
    }

    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required'
        ]);
        $newCategory = Category::create($data);
        return $this->showOne($newCategory, 201);
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required'
        ]);
        $category->fill($data);
        if ($category->isClean()) {
            return $this->errorRespond('you need to specify a different value', 422);
        }
        $category->save();
        return $this->showOne($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showOne($category);
    }
}
