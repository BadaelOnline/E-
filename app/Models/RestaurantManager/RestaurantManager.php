<?php

namespace App\Models\RestaurantManager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantManager extends Model
{
    use HasFactory;
    protected $table='restaurant_managers';
    protected $fillable =['Id','user_id','is_active','is_approved'];
    protected $hidden   =['created_at','updated_at','user_id','pivot'];

}
