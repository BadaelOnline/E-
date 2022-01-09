<?php

namespace App\Models\Currencies;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table='currencies';
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    protected $fillable=['id','currency','code','symbol'];
}
