<?php


namespace App\Scopes;


use App\Models\Clinic\Clinic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ClinicScope implements \Illuminate\Database\Eloquent\Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
      $builder ->where('clinics.is_active','=',1)
          ->select(['clinics.id','clinics.is_active','clinics.is_approved','clinics.name','clinic_translation.locale']);
    }
}
