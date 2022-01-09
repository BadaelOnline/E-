<?php

namespace App\Models\Plans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table ='plans';
    protected $fillable=['name','price_per_month'];
    protected $hidden=['created_at', 'updated_at'];

    public function Subscription(){
        return $this->hasMany(Subscription::class,'plan_id');
    }
}
