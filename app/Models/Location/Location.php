<?php

namespace App\Models\Location;

use App\Models\Stores\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';
    protected $fillable = ['id','name', 'country', 'governorate',
        'street', 'longitude', 'phone_number', 'latitude', 'is_active'];
    protected $hidden = ['pivot','created_at', 'updated_at'];

    public function Store():HasMany
    {
        return $this->hasMany(Store::class, 'location_id');
    }

    public function User(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_locations', 'location_id', 'user_id');
    }

}
