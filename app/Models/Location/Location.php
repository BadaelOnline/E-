<?php

namespace App\Models\Location;

use App\Models\Stores\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table='location';
    protected $fillable=['id','name','address','latitude','longitude','is_active'];
    protected $hidden=['created_at','updated_at','user_id'];

    public function Store(){
        return $this->hasMany(Store::class,'location_id');
    }

}
