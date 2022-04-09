<?php

namespace App\Models\Stores;

use App\Models\Products\Product;
use App\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Config;

class StoreProduct extends Pivot
{
    use HasFactory;

    protected $table = 'stores_products';
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at', 'updated_at', 'is_active', 'is_appear'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'is_appear' => 'boolean'
    ];
    protected $fillable = [
        'price', 'quantity', 'is_active', 'is_approve', 'store_id', 'product_id'
    ];

    public function scopeGetStoreProductsList($q)
    {
        return $q = Product::withoutGlobalScope(ProductScope::class)
            ->select('id')
            ->with(['ProductTranslation' => function ($q) {
                return $q->where('product_translations.local',
                    '=',
                    Config::get('app.locale'))
                    ->select(['product_translations.name',
                        'product_translations.product_id',
                        'product_translations.short_des'])
                    ->get();
            }, 'StoreProduct' => function ($qq) {
                $qq->with('StoreProductDetails')->get();
            }]);
    }

    public function Store()
    {
        return $this->belongsTo(Store::class);
    }

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

    public function StoreProductDetails()
    {
        return $this->hasMany(StoreProductDetails::class,
            'store_products_id');
    }
}
