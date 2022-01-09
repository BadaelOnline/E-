<?php

namespace App\Models\MedicalDevice;

use App\Models\medicalDevice\MedicalDevice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalDeviceTranslation extends Model
{
    use HasFactory;

    protected $table='medical_device_translation';
    protected $fillable=['id','medical_device_id','name','locale','description'];
    protected $hidden=['medical_device_id','created_at','updated_at'];
    public function medicaldevice()
    {
        return $this->belongsTo(MedicalDevice::class);
    }
}
