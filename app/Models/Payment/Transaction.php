<?php

namespace App\Models\Payment;

use App\Models\Plans\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table ='transactions';
    protected $fillable=['payment_method_id','total'];
    protected $hidden=['created_at', 'updated_at'];

    public function Subscription()
    {
        return $this->hasMany(Subscription::class,'transaction_id');
    }
    public function Payment_Method()
    {
        return $this->belongsTo(Payment_Method::class,'payment_method_id');
    }
}
