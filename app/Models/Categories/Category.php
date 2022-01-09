<?php

namespace App\Models\Categories;

use App\Models\Images\CategoryImages;
use App\Models\Products\Product;
use App\Scopes\CategoryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = [
      'slug', 'parent_id', 'image', 'is_active','category_id','section_id'];
    protected $hidden = [
        'created_at', 'updated_at','section_id','category_id','parent_id','pivot'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'image'=>'string'
    ];
    public $timestamps = false;
    public function getIsActiveAttribute($value)
    {
        return $value==1 ? 'Active' : 'Not Active';
    }
    public function getImageAttribute($image)
    {
        return  'images/categories' . '/' . $image ;
    }
    //________________ scopes begin _________________//
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new CategoryScope);
    }
    public function scopeSelection($query)
    {
        return $query->select('id')->get();
    }
    //________________ scopes end _________________//
    //________________ relationShips _________________//
    public function CategoryTranslation()
    {
        return $this->hasMany(CategoryTranslation::class,'category_id');
    }
    public function Section()
    {
        return $this->belongsTo(Section::class,'section_id');
    }
    public function Parent()
    {
        return $this->belongsTo(Category::class,'parent_id');
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
}
//1992fahed1992@
//651
