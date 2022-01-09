<?php

namespace App\Models\RestaurantCategory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantCategoryTranslation extends Model
{
    use HasFactory;
    protected $table='restaurant_category_translations';
    protected $fillable=['id','restaurant_category_id','locale','name','short_description','long_description'];

    public function restaurantCategory()
    {
        return $this->belongsTo(RestaurantCategory::class,'restaurant_category_id');
    }
}
