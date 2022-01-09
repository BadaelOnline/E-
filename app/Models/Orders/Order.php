<?php

namespace App\Models\Orders;

use App\Models\Payment\Payment_Method;
use App\Models\Shipping\Shipping_Method;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table ='orders';
    protected $fillable = [
        'user_id','Payment_Method_id','shipping_id',
        'total', 'state','is_active'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function Order_details()
    {
        return $this->hasMany(Order_Details::class,'order_id');
    }
    public function Payment_Method()
    {
        return $this->belongsTo(Payment_Method::class,'Payment_Method_id');
    }
    public function User()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function Shipping_Method()
    {
        return $this->belongsTo(Shipping_Method::class,'order_id');
    }
}
