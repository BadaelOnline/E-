<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class RestaurantScope implements \Illuminate\Database\Eloquent\Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
        $builder->join('restaurant_translations','restaurants.id','=','restaurant_translations.restaurant_id')
            ->where('restaurant_translations.locale','=',config::get('app.locale'))
            ->select('restaurants.id', 'restaurants.is_active', 'restaurants.is_approved', 'restaurants.image',
                'restaurant_translations.title','restaurant_translations.short_description',
                'restaurant_translations.long_description','restaurant_translations.locale');
    }
}
