<?php

namespace App\Models\Clinic;

use App\Models\Doctors\Doctor;
use App\Scopes\ClinicScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Clinic extends Model
{
    use HasFactory;

    protected $table='clinics';
    protected $fillable=['id','doctor_id','location_id','phone_number','name','active_time_id','is_active','is_approved'];
    protected $hidden=['created_at','updated_at','doctor_id','pivot'];
    // local Scope
    public function scopeNotActive($query)
    {
        return $query->where('is_active',0);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
