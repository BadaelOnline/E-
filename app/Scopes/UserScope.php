<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;

class UserScope implements Scope
{
    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {

        $builder->join('user_translation', 'users.id', '=', 'user_translation.user_id')
            ->where('user_translation.local', '=', Config::get('app.locale'))
            ->select([
                'users.id','user_translation.first_name',
                'user_translation.last_name',
                'user_translation.local','age','location_id',
                'social_media_id', 'users.is_active',
                'image','email', 'users.is_active'
                ]);
    }
}
