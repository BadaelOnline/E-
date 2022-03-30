<?php

namespace App\Models\Offer;

use App\Models\Comment\Comment;
use App\Models\Stores\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table='offers';
    protected  $fillable=['id','user_email','store_id','store_product_id','offer_price','selling_quantity'
    ,'started_at','ended_at','is_active','is_offer'];

    protected $hidden=['store_id','store_product_id','created_at','updated_at'];

    //local scope
    public function scopeNotActive($query)
    {
        return $query->where('is_active',0)->get();
    }
    public function scopeAdvertisement($query)
    {
        return $query->select('id')->where('is_active',1)->get();
    }

    public function OfferTranslation()
    {
        return $this->hasMany(OfferTranslation::class);
    }

    public function Store()
    {
        return $this->belongsTo(Store::class);
    }

    public function Comment()
    {
        return $this->hasMany(Comment::class);
    }
}
