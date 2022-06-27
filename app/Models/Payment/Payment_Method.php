<?php

namespace App\Models\Payment;

use App\Models\Orders\Order;
use App\Models\Stores\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_Method extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table ='payment_methods';
    protected $fillable=['name'];
    protected $hidden = [
        'created_at', 'updated_at','pivot'
    ];

    public function Transaction()
    {
        return $this->hasMany(Transaction::class,'transaction_id');
    }
    public function Store()
    {
        return $this->belongsToMany(Store::class,
            'store_payment_methods',
            'payment_method_id',
        'store_id');
    }
    public function Order()
    {
        return $this->hasMany(Order::class,'payment_id');
    }
}
