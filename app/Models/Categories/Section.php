<?php

namespace App\Models\Categories;

use App\Models\Products\Product;
use App\Models\Stores\Store;
use App\Models\Stores\StoreProduct;
use App\Scopes\SectionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categories\SectionTranslation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Config;

class Section extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'sections';
    public $timestamps = true;
    protected $fillable = [
        'slug', 'image', 'is_active'];
    public function getIsActiveAttribute($value)
    {
        return $value==1 ? 'Active' : 'Not Active';
    }
    public function getImageAttribute($image)
    {
        return  'images/sections' . '/' . $image ;
    }
//________________ scopes begin _________________//
//    public function scopeAllStores($value)
//    {
//        return $value->join('section_translations', 'sections.id', '=','section_translations.section_id' )
//            ->where('section_translations.local','=',Config::get('app.locale'))
//            ->select(['sections.id','sections.image','sections.created_at','section_translations.name','section_translations.description','section_translations.local']);
//    }
    public function scopeOneStore($value)
    {
        return $value->join('section_translations', 'sections.id', '=','section_translations.section_id' )
            ->where('section_translations.local','=',Config::get('app.locale'))
            ->select(['sections.id','sections.image','sections.image','sections.created_at',
                'section_translations.name','section_translations.description'])
            ->with(['Category'=>function(Builder  $query){
                return $query=Category::with(['CategoryTranslation'=>function(Builder  $qq){
                    return $qq->select('name');
                }]);
    }]);
    }
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new SectionScope);
    }
    public function scopeSelectById($query)
    {
       return $query->join('section_translations', 'sections.id', '=', 'section_translations.section_id')
            ->where('section_translations.local', '=', Config::get('app.locale'))
            ->select(['sections.id', 'sections.is_active', 'sections.image', 'sections.created_at',
                'section_translations.name', 'section_translations.description', 'section_translations.local']);
    }
    //________________ scopes end _________________//
    public function SectionTranslation()
    {
        return $this->hasMany(SectionTranslation::class,'section_id');
    }
    public function Category()
    {
        return $this->hasMany(Category::class,'section_id');
    }
    public function Store()
    {
        return $this->belongsToMany(
            Store::class,
            'stores_sections',
            'section_id',
            'store_id',
            'id',
            'id');
    }
    public function Product()
    {
        return $this->belongsToMany(
            Product::class,
            'products_sections',
            'section_id',
            'product_id');
    }
    public function products(){
        return $this->hasManyThrough(
            Category::class,
            ProductCategory::class,
        'product_id',
        'id',
        'id',
        'id');
    }
}
