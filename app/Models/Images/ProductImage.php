<?php

namespace App\Models\Images;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $primaryKey ='id';
    protected $table ='product_images';
    protected $fillable =['product_id','image','is_cover','is_check'];
    protected $hidden=['product_id','created_at','updated_at'];
    protected $casts = [
        'is_cover' => 'boolean',
        'image'=>'string'
    ];

    public function getIsCoverAttribute($value)
    {
        return $value==1 ? 'cover' : 'Not cover';
    }
//
    public function getImageAttribute($image)
    {
        return  'images/products' . '/' .  $this->product_id . '/' . $image ;
    }

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }
}
