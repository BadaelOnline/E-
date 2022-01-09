<?php

namespace App\Models\Brands;

use App\Models\Products\Product;
use App\Models\Stores\Store;
use App\Scopes\BrandScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table='brands';
    protected $hidden = [
        'created_at', 'updated_at','pivot'
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
    protected $fillable=['id','image','slug','is_active'];
    public function getImageAttribute($image)
    {
        return  'images/brands' . '/' . $image ;
    }
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new BrandScope);
    }
    public function BrandTranslation()
    {
        return $this->hasMany(BrandTranslation::class);
    }
    public function Product()
    {
        return $this->hasMany(Product::class,'brand_id');
    }
    public function Store()
    {
        return $this->belongsToMany(Store::class,
            'store_brand',
            'brand_id',
            'store_id',
            'id',
            'id');
    }
}
