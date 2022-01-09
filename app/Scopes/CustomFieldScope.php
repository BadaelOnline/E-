<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;

class CustomFieldScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('custom__fields__translations', 'custom_fields.id', '=', 'custom__fields__translations.custom_field_id')
            ->where('custom__fields__translations.local', '=', Config::get('app.locale'))
            ->select(['custom_fields.id','custom_fields.image','custom_fields.is_active', 'custom__fields__translations.name','custom__fields__translations.description', 'custom__fields__translations.local']);
    }
}
