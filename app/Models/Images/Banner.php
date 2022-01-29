<?php

namespace App\Models\Images;

use App\Models\Stores\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table ='banners';
    protected $fillable = ['store_id','image','description','is_appear','is_active','created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];
    public function getImagePathAttribute($value)
    {
        return $value=public_path('images/stores/banners' . '/' . $this -> store_id . '/' . $this->image);
    }
    public function Store()
    {
        return $this->belongsTo(Store::class);
    }
}
