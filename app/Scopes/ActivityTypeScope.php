<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;

class ActivityTypeScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('activity_type_translations', 'activities_type.id', '=', 'activity_type_translations.activity_type_id')
            ->where('activity_type_translations.local', '=', Config::get('app.locale'))
            ->select([
                'activities_type.id','activities_type.is_active','activities_type.activity_id',
                'activity_type_translations.name']);
    }
}
