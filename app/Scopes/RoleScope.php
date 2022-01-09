<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;

class RoleScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('role_translation', 'role_translation.role_id', '=', 'roles.id')
            ->where('role_translation.local', '=', Config::get('app.locale'))
            ->select([
                'roles.id', 'roles.is_active', 'roles.slug',
                'role_translation.name', 'role_translation.description',
                'role_translation.display_name', 'role_translation.local']);
    }
}
