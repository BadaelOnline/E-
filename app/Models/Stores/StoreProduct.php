<?php

namespace App\Models\Stores;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StoreProduct extends Pivot
{
    use HasFactory;
    protected $table = 'stores_products';
    protected $primaryKey = 'id';
    protected $hidden =
        [
        'created_at', 'updated_at','is_active','is_appear'
        ];
    protected $casts = [
        'is_active' => 'boolean',
        'is_appear'=> 'boolean'
    ];
    protected $fillable =
        [
        'price','quantity','is_active','is_approve','store_id','product_id'
        ];
    public function Store()
    {
        return $this->belongsTo(Store::class);
    }
    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
    public function products(){
        return $this->hasManyThrough
        (
            Product::class,
            StoreProduct::class,
            'store_id',
            'id',
            'id',
            'id'
        );
    }
}
