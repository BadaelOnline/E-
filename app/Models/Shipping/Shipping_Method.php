<?php

namespace App\Models\Shipping;

use App\Models\Orders\Order;
use App\Models\Stores\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping_Method extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table ='shipping_method';
    protected $fillable=['name'];
    protected $hidden = [
        'created_at', 'updated_at','pivot'
    ];
    public function Shipping_Method()
    {
        return $this->hasMany(Order::class,'order_id');
    }
    public function Store()
    {
        return $this->belongsToMany(Store::class,
            'store_shipping',
        'shipping_id',
        'store_id');
    }
}
