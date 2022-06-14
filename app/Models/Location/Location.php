<?php

namespace App\Models\Location;

use App\Models\Stores\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table='locations';
    protected $fillable=['id','country','governorate','street','longitude','is_active'];
    protected $hidden=['created_at','updated_at','user_id'];

    public function Store(){
        return $this->hasMany(Store::class,'location_id');
    }
    public function User(){
        return $this->hasMany(User::class,'location_id');
    }

}
