<?php

namespace App\Models\Orders;

use App\Models\Products\Product;
use App\Models\Stores\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Details extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table ='order_details';
    protected $fillable = [
        'store_id','order_id','product_id',
        'qty', 'price','is_appear','is_active'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function Order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    public function Product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function Store()
    {
        return $this->belongsTo(Store::class,'store_id');
    }
}
