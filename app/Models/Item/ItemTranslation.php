<?php

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTranslation extends Model
{
    use HasFactory;
    protected $table='item_translations';
    protected $fillable=['id','restaurant_product_id','locale','name','short_description','long_description'];

}
