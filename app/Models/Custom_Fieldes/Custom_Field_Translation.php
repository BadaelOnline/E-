<?php

namespace App\Models\Custom_Fieldes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custom_Field_Translation extends Model
{
    use HasFactory;
    protected $table='custom__fields__translations';
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at', 'updated_at'
    ];
//    protected $casts = [
//        'is_active' => 'boolean',
//        'is_approve'=> 'boolean'
//    ];
//    protected $table = 'stores';
    protected $fillable = [
        'name','description','local','custom_field_id'
    ];
    public function Custom_Field()
    {
        return $this->belongsToMany(Custom_Field::class);
    }

}
