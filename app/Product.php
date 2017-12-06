<?php

namespace App;

use App\Category;
use App\Seller;
use App\Transaction;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $transformer = ProductTransformer::class;
    protected $fillable = ['name', 'description', 'quantity', 'status', 'image', 'seller_id'];
    protected $hidden = ['pivot'];
    
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function isAvailable()
    {
        return $this->status == true;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function transactions()
    {
        return $this->belongsTo(Transaction::class, 'id');
    }
}
