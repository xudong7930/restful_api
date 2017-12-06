<?php
use Illuminate\Http\Request;

Route::resource('buyers', 'Buyer\BuyerController', ['only' => ['index', 'show']]);
Route::resource('buyers.transactions', 'Buyer\BuyerTransactionController', ['only' =>['index']]);
Route::resource('buyers.products', 'Buyer\BuyerProductController', ['only' => 'index']);
Route::resource('buyers.sellers', 'Buyer\BuyerSellerController', ['only' => 'index']);
Route::resource('buyers.categories', 'Buyer\BuyerCategoryController', ['only' => 'index']);

Route::resource('categories', 'Category\CategoryController');
Route::resource('categories.products', 'Category\CategoryProductController', ['only'=>['index']]);
Route::resource('categories.sellers', 'Category\CategorySellerController', ['only'=>['index']]);
Route::resource('categories.transactions', 'Category\CategoryTransactionController', ['only'=>['index']]);
Route::resource('categories.buyers', 'Category\CategoryBuyerController', ['only'=>['index']]);


Route::resource('products', 'Product\ProductController', ['only' => ['index', 'show']]);
Route::resource('products.buyers', 'Product\ProductBuyerController', ['only' => ['index']]);
Route::resource('products.transactions', 'Product\ProductTransactionController', ['only' => ['index']]);
Route::resource('products.categories', 'Product\ProductCategoryController', ['only' => ['index', 'update', 'destroy']]);
Route::resource('products.buyers.transactions', 'Product\ProductBuyerTransactionController', ['only' => ['store']]);

Route::resource('sellers', 'Seller\SellerController', ['only' => ['index', 'show']]);
Route::resource('sellers.transaction', 'Seller\SellerTransactionController', ['only' => ['index']]);
Route::resource('sellers.category', 'Seller\SellerCategoryController', ['only' => ['index']]);
Route::resource('sellers.buyer', 'Seller\SellerBuyerController', ['only' => ['index']]);
Route::resource('sellers.products', 'Seller\SellerProductController', ['except' => ['show', 'create', 'edit']]);



Route::resource('transactions', 'Transaction\TransactionController', ['only' => ['index', 'show']]);
Route::resource('transactions.categories', 'Transaction\TransactionCategoryController', ['only' => ['index']]);
Route::resource('transactions.sellers', 'Transaction\TransactionSellerController', ['only' => ['index']]);
Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);
Route::get('user/verify/{token}', 'User\UserController@verify')->name('verify');
Route::get('user/{user}/resend', 'User\UserController@resend')->name('resend');
