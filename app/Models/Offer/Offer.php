<?php

namespace App\Models\Offer;

use App\Models\Comment\Comment;
use App\Models\Stores\StoreProductDetails;
use App\Scopes\OfferScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use App\Scopes\StoreScope;


class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';
    protected $fillable = ['id', 'user_email', 'offer_price', 'selling_quantity'
        , 'started_at', 'ended_at', 'is_active', 'is_offer'];
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    //local scope
    public function scopeNotActive($query)
    {
        return $query->where('is_active', 0)->get();
    }

    public function scopeAdvertisement($query)
    {
        return $query->select('id')->where('is_active', 1)->get();
    }

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new OfferScope);
    }

    public function OfferTranslation()
    {
        return $this->hasMany(OfferTranslation::class, 'offer_id');
    }

    public function StoreProductDetails()
    {
        return $this->belongsToMany(
            StoreProductDetails::class,
            'store_products_offers',
            'offer_id',
            'store_product_id'
        );
    }

    public function scopeGetStoreProductsList($query)
    {
        return $query->with(['StoreProductDetails' => function ($q) {
            return $q->with(['StoreProduct' => function ($q1) {
                return $q1
                    ->join('stores', 'stores_products.store_id', '=', 'stores.id')
                    ->join('store_translations', 'stores.id', '=', 'store_translations.store_id')
                    ->where('store_translations.local', '=', Config::get('app.locale'))
                    ->select([
                        'stores.id',
                        'stores.logo',
                        'store_translations.name'
                    ]);
            }]);
        }]);
    }

    public function Comment()
    {
        return $this->hasMany(Comment::class);
    }
}
