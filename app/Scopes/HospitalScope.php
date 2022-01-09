<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class HospitalScope implements \Illuminate\Database\Eloquent\Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
        $builder->join('hospital_translations','hospital_translations.hospital_id','=','hospitals.id')
            ->where('hospital_translations.locale','=',config::get('app.locale'))
            ->select('hospitals.id','hospitals.is_active','hospitals.is_approved',
                'hospital_translations.name','hospital_translations.description','hospital_translations.locale');
    }
}
