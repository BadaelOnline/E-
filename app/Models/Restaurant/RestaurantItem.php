<?php

namespace App\Models\Restaurant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantItem extends Model
{
    use HasFactory;

    protected $table='restaurant_item';
    protected $fillable =['Id','restaurant_id','item_id','price',
        'quantity','is_active','is_approved'];

    protected $hidden=['restaurant_id','item_id',
        'created_at','updated_at'];

}
