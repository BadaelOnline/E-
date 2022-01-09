<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class RestaurantCategoryScope implements \Illuminate\Database\Eloquent\Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
        $builder->join('restaurant_category_translations','restaurant_categories.id','=','restaurant_category_translations.restaurant_category_id')
            ->where('restaurant_category_translations.locale','=',config::get('app.locale'))
            ->select('restaurant_categories.id','restaurant_categories.is_active','restaurant_categories.is_approved',
                'restaurant_category_translations.name','restaurant_category_translations.short_description',
                'restaurant_category_translations.long_description','restaurant_category_translations.locale');

    }
}
