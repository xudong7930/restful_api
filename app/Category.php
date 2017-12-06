<?php

namespace App;

use App\Product;
use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes ;

    public $transformer = CategoryTransformer::class;
    protected $table = 'category';
    protected $fillable = ['name', 'description'];
    protected $dates = ['deleted_at'];
    protected $hidden = ['pivot'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
