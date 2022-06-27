<?php

namespace App\Models\Categories;

use App\Models\Custom_Fieldes\Custom_Field;
use App\Models\Images\CategoryImages;
use App\Models\Products\Product;
use App\Scopes\CategoryScope;
use App\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'slug', 'parent_id', 'image', 'is_active', 'category_id', 'section_id'];
    protected $hidden = [
        'created_at', 'updated_at', 'section_id', 'category_id', 'parent_id', 'pivot'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'image' => 'string'
    ];

    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new CategoryScope);
    }

    public function getIsActiveAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Not Active';
    }

    //________________ scopes begin _________________//

    public function getImageAttribute($image)
    {
        return 'images/categories' . '/' . $image;
    }

    public function scopeGetCategoryProductsList($q)
    {
        return $q = Product::withoutGlobalScope(ProductScope::class)
            ->select('id')
            ->with(['ProductTranslation' => function ($q) {
                return $q->where('product_translations.local',
                    '=',
                    Config::get('app.locale'))
                    ->select(['product_translations.name',
                        'product_translations.short_des',
                        'product_translations.long_des',
                        'product_translations.product_id'])
                    ->get();
            }, 'StoreProduct' => function ($qq) {
                $qq->with(['StoreProductDetails' => function ($qqq) {
                    $qqq->select(['store_product_details.id', 'store_product_details.store_products_id', 'store_product_details.price'])->get();
                }])->where('is_active', 1)->get();
            }]);
    }

    public function scopeSelection($query)
    {
        return $query->select('id')->get();
    }
    //________________ scopes end _________________//
    //________________ relationShips _________________//
    public function CategoryTranslation()
    {
        return $this->hasMany(CategoryTranslation::class, 'category_id');
    }

    public function Section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function Parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function Product()
    {
        return $this->belongsToMany(
            Product::class,
            'products_categories',
            'category_id',
            'product_id');
    }

    public function ProductCategory()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function CustomField()
    {
        return $this->belongsToMany(
            Custom_Field::class,
            'category_custom_fields',
            'category_id',
            'custom_field_id');
    }
}

//1992fahed1992@
//651
