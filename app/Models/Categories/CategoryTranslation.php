<?php

namespace App\Models\Categories;

use App\Models\Language\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['name','local','category_id'];
    public $timestamps = false;
    protected $hidden=['category_id','local','created_at','updated_at'];

    /////////////////Begin relation here/////////////////////
    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
}
