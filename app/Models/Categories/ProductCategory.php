<?php

namespace App\Models\Categories;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductCategory extends Pivot
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'products_categories';
    public $timestamps = true;
//    protected $fillable = [];


    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
