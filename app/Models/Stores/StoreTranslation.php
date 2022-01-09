<?php

namespace App\Models\Stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreTranslation extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $fillable = [
        'title','local','store_id'
    ];
    public function Store()
    {
        return $this->belongsTo(Store::class);
    }
}
