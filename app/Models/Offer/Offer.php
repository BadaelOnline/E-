<?php

namespace App\Models\Offer;

use App\Models\Comment\Comment;
use App\Models\Products\Product;
use App\Models\Stores\Store;
use App\Models\Stores\StoreProduct;
use App\Scopes\OfferScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';
    protected $fillable = ['id', 'user_email', 'offer_price', 'selling_quantity'
        , 'started_at', 'ended_at', 'is_active', 'is_offer'];

    protected $hidden = ['created_at', 'updated_at'];

    //local scope
    public function scopeNotActive ($query)
    {
        return $query->where ('is_active', 0)->get ();
    }

    public function scopeAdvertisement ($query)
    {
        return $query->select ('id')->where ('is_active', 1)->get ();
    }

    protected static function booted ()
    {
        parent::booted ();
        static::addGlobalScope (new OfferScope);
    }

    public function OfferTranslation ()
    {
        return $this->hasMany (OfferTranslation::class,'offer_id');
    }


    public function storeProduct ()
    {
        return $this->belongsToMany (
            StoreProduct::class,
            'store_products_offers',
            'offer_id',
            'store_product_id',
            'id',
            'id'
        );
    }

    public function Comment()
    {
        return $this->hasMany(Comment::class);
    }
}
