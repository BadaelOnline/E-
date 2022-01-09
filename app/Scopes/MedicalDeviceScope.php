<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class MedicalDeviceScope implements \Illuminate\Database\Eloquent\Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
     $builder->join('medical_device_translation','medical_device_translation.medical_device_id','=','medical_devices.id')
            ->where('medical_device_translation.locale','=',config::get('app.locale'))
            ->select('medical_devices.id','medical_devices.is_active','medical_devices.is_approved','medical_devices.image'
                ,'medical_device_translation.name' ,'medical_device_translation.description' ,'medical_device_translation.locale');


    }
}
