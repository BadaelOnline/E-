<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;

class RestaurantTypeScope implements Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
        $builder->join('restaurant_type_translations','restaurant_types.id','=','restaurant_type_translations.restaurant_type_id')
            ->where( 'restaurant_type_translations.locale','=',config::get('app.locale'))
            ->select('restaurant_types.id','restaurant_types.is_active','restaurant_types.is_approved',
                'restaurant_type_translations.title','restaurant_type_translations.short_description',
                'restaurant_type_translations.long_description','restaurant_type_translations.locale');
    }
}
