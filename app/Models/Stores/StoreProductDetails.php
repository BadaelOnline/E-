<?php

namespace App\Models\Stores;

use App\Models\Custom_Fieldes\Custom_Field_Value;
use App\Models\Orders\Order_Details;
use App\Models\Stores\StoreProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreProductDetails extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'store_product_details';
    protected $fillable = ['price', 'quantity', 'store_products_id'];
    protected $hidden = ['created_at', 'updated_at','pivot'];
//    protected $hidden = ;

    public function Custom_Field_Value()
    {
        return $this->belongsToMany(
            Custom_Field_Value::class,
            'details_custom_values',
            'store_products_details_id',
            'custom_field_value_id');
    }

    public function StoreProduct(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StoreProduct::class ,'store_products_id');
    }
    public function Order_Details()
    {
        return $this->belongsTo(Order_Details::class ,'store_products_id');
    }
}
