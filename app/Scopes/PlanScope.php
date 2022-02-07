<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;

class PlanScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('plan_translations', 'plans.id', '=', 'plan_translations.plan_id')
            ->where('plan_translations.local', '=', Config::get('app.locale'))
            ->select([
                'plans.id', 'plans.is_active', 'plans.discount',
                'plans.num_of_month', 'plans.activity_id', 'plans.features',
                'plans.price', 'plan_translations.name'
            ]);
    }
}
