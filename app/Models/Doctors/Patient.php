<?php

namespace App\Models\Doctors;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $table='patients';
    protected $fillable =['id','gender','social_status','blood_type','note',
        'medical_file_number','medical_file_date','review_date'
        ,'PDF','is_active','is_approved'];

    protected $hidden=['created_at','updated_at'];
    //local scope
    public function scopeIsActive($query)
    {
        return $query->where('is_active',1)->get();
    }

    public function scopeNotActive($query)
    {
        return $query->where('is_active',0)->get();
    }

    public function doctor()
    {
        return $this -> belongsToMany(Doctor::class,'doctor_patient','patient_id','doctor_id','id','id');
    }

    function User()
    {
        return $this->belongsTo(User::class);
    }
}
