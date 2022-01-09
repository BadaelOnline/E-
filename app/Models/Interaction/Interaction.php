<?php

namespace App\Models\Interaction;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;
    protected $table='interactions';
    protected $fillable=['id','user_id','offer_id','interaction_type','is_active'];

    //local scope
    public function scopeNotActive($query)
    {
        return $query->where('is_active',0)->get();
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
