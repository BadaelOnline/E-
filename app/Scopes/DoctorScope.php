<?php


namespace App\Scopes;

use App\Models\Doctors\DoctorTranslation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class DoctorScope implements Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
      $builder->join('doctor_translation','doctors.id','=','doctor_translation.doctor_id')
              ->join('users','users.id','=','doctors.user_id')
              ->join('user_translation','user_translation.id','=','doctors.user_id')
              ->where('doctor_translation.locale','=',config::get('app.locale'))

              ->select(['doctors.id','doctors.is_active','doctors.is_approved',
              'doctor_translation.description','users.social_media_id'
              ,'user_translation.first_name','user_translation.last_name']);

    }
}
