<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;

class PermissionScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('permission_translation', 'permission_translation.permission_id', '=', 'permissions.id')
            ->where('permission_translation.local', '=', Config::get('app.locale'))
            ->select([
                'permissions.id', 'permissions.is_active', 'permissions.slug',
                'permission_translation.name', 'permission_translation.description',
                'permission_translation.display_name', 'permission_translation.local']);
    }
}
