<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class RestaurantProductScope implements \Illuminate\Database\Eloquent\Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
        $builder->join('restaurant_product_translations','restaurant_products.id','=','restaurant_product_translations.restaurant_product_id')
            ->where('restaurant_product_translations.locale','=',config::get('app.locale'))
            ->select('restaurant_products.id','restaurant_products.is_active','restaurant_products.is_approved',
                'restaurant_product_translations.name','restaurant_product_translations.short_description',
                'restaurant_product_translations.long_description','restaurant_product_translations.locale');

    }
}
