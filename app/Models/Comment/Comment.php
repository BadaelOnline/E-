<?php

namespace App\Models\Comment;

use App\Models\Offer\Offer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table='comments';
    protected $fillable=['id','user_id','offer_id','comment','is_active','is_approved'];

    //local scope

    public function scopeNotActive($query)
    {
        return $query->where('is_active',0)->get();
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
