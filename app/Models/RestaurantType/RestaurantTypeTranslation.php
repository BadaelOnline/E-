<?php

namespace App\Models\RestaurantType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTypeTranslation extends Model
{
    use HasFactory;
    protected $table='restaurant_type_translations';
    protected $fillable =['Id','locale','title','short_description','long_description','restaurant_type_id'];
    public $timestamps=false;
    protected $hidden=['created_at','updated_at'];


    public function restaurantType()
    {
        return $this->belongsTo(RestaurantType::class,'restaurant_type_id');
    }
}
