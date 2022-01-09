<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AppointmentScope implements \Illuminate\Database\Eloquent\Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
       $builder->join('appointment_translations','appointments.id','=','appointment_translations.appointment_id')
           ->where('appointment_translations.locale','=',config::get('app.locale'))
           ->select(['appointments.id','appointments.is_active','appointments.is_approved','appointments.morning_evening'
               ,'appointment_translations.description']);
    }
}

