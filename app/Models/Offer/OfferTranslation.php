<?php

namespace App\Models\Offer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferTranslation extends Model
{
    use HasFactory;
    protected $table='offer_translations';
    protected  $fillable=['id','name','short_desc','long_desc','locale'
        ,'offer_id'];

    protected $hidden=['offer_id'];

    public function Offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
