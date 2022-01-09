<?php


namespace App\Scopes;


use App\Models\Specialty\Specialty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SpecialtyScope implements \Illuminate\Database\Eloquent\Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
         $builder->join('specialty_translation','specialty_translation.specialty_id','=','specialties.id')
             ->where('specialty_translation.locale','=',config::get('app.locale'))
             ->select('specialties.id','specialties.is_active','specialties.graduation_year',
            'specialty_translation.name','specialty_translation.description','specialty_translation.locale');
    }
}
