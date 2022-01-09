<?php

namespace App\Models\Plans;

use App\Models\Payment\Transaction;
use App\Models\Stores\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table ='subscriptions';
    protected $fillable=['store_id','plan_id','start_date','end_date','transaction_id'];
    protected $hidden=['created_at', 'updated_at'];

    public function Transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }
    public function Plan(){
        return $this->belongsTo(Plan::class,'plan_id');
    }
    public function Store(){
        return $this->belongsTo(Store::class,'store_id');
    }
}
