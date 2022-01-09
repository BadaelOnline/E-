<?php

namespace App\Models\Specialty;

use App\Models\Specialty\Specialty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialtyTranslation extends Model
{
    use HasFactory;

    protected $table='specialty_translation';
    protected $fillable=['id','specialty_id','name','locale','description'];

    protected $hidden=['specialty_id','created_at','updated_at'];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
