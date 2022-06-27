<?php

namespace App\Models\Stores;

use App\Models\Brands\Brand;
use App\Models\Categories\Section;
use App\Models\Currencies\Currency;
use App\Models\Images\StoreImage;
use App\Models\Location\Location;
use App\Models\Offer\Offer;
use App\Models\Orders\Order;
use App\Models\Orders\Order_Details;
use App\Models\Payment\Payment_Method;
use App\Models\Plans\Subscription;
use App\Models\Products\Product;
use App\Models\Shipping\Shipping_Method;
use App\Models\SocialMedia\SocialMedia;
use App\Models\Stores_Orders\Stores_Order;
use App\Models\User;
use App\Models\Images\Banner;
use App\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at', 'updated_at', 'pivot'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'is_approve' => 'boolean'
    ];
    protected $table = 'stores';
    protected $fillable = [
        'currency_id', 'location_id', 'social_media_id',
        'activity_type_id', 'owner_id', 'street_id',
        'is_active', 'logo', 'is_approved'
        , 'is_active', 'section_id', 'created_at', 'updated_at',
    ];

    public function getIsActiveAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Not Active';
    }

    public function getIsApprovedAttribute($value)
    {
        return $value == 1 ? 'Approved' : 'Not Approved';
    }

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new StoreScope);
    }

    public function StoreTranslation()
    {
        return $this->hasMany(
            StoreTranslation::class,
            'store_id');
    }

//    public function Product()
//    {
//        return $this->belongsToMany(
//            Product::class,
//            'stores_products',
//            'store_id',
//            'product_id',
//            'id',
//            'id')
//            ->withPivot(['price', 'quantity'])
//            ->withTimestamps();
//    }

    public function Section()
    {
        return $this->belongsToMany(
            Section::class,
            'stores_sections',
            'store_id',
            'section_id',
            'id',
            'id'
        );
    }

    public function StoreProduct()
    {
        return $this->hasMany(StoreProduct::class);
    }

    public function Brand()
    {
        return $this->belongsToMany(
            Brand::class,
            'store_brand',
            'store_id',
            'brand_id',
            'id',
            'id'
        );
    }

    public function StoreImage()
    {
        return $this->hasMany(StoreImage::class);
    }

    public function Offer()
    {
        return $this->hasMany(Offer::class);
    }

    public function Subscription()
    {
        return $this->hasMany(Subscription::class, 'store_id');
    }

    public function Payment_Method()
    {
        return $this->belongsToMany(Payment_Method::class,
            'store_payment_methods',
            'store_id',
            'payment_method_id');
    }

    public function Order()
    {
        return $this->belongsToMany(
            Order::class,
            'store_orders',
            'store_id',
            'order_id');
    }

    public function Shipping_Method()
    {
        return $this->belongsToMany(Shipping_Method::class,
            'store_shipping',
            'store_id',
            'shipping_id');
    }

    public function User()
    {
        return $this->belongsToMany(
            User::class,
            'store_users',
            'store_id',
            'user_id');
    }

    public function Owned()
    {
        return $this->hasMany(User::class, 'owner_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function SocialMedia()
    {
        return $this->belongsTo(SocialMedia::class, 'social_media_id');
    }

    public function Location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function Banner()
    {
        return $this->HasMany(Banner::class);
    }
}
