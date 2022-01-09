<?php

namespace App\Models\DoctorRate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctors\Doctor;
class DoctorRate extends Model
{
    use HasFactory;
    protected $table='doctor_rates';
    protected $fillable=['id','rate','doctor_id'];
    protected $hidden=['created_at','updated_at'];

    public $timestamps=false;

    //scope
    public function scopeIsActive($query)
    {
        return $query->where('is_active',1)->get();
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
