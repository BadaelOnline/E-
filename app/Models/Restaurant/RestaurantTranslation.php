<?php

namespace App\Models\Restaurant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTranslation extends Model
{
    use HasFactory;
    protected $table='restaurant_translations';
    protected $fillable =['Id','locale','title','short_description','long_description','restaurant_type_id'];
    protected $hidden=['restaurant_type_id','created_at','updated_at'];

    public function restaurant()
    {
        return$this->belongsTo(Restaurant::class);
    }
}
