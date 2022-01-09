<?php

namespace App\Models\RestaurantProduct;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantProductTranslation extends Model
{
    use HasFactory;
    protected $table='restaurant_product_translations';
    protected $fillable=['id','restaurant_product_id','locale','name','short_description','long_description'];

}
