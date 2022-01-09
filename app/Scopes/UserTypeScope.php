<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;

class UserTypeScope implements Scope
{
    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {

        $builder->join('type_users_translation', 'type_users.id', '=', 'type_users_translation.type_users_id')
            ->where('type_users_translation.local', '=', Config::get('app.locale'))
            ->select([
                'type_users.id','type_users_translation.name',
                'type_users_translation.description',
                'type_users_translation.local'
                ]);
    }
}
