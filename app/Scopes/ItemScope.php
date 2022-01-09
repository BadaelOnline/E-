<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class ItemScope implements \Illuminate\Database\Eloquent\Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: Implement apply() method.
        $builder->join('item_translations','items.id','=','item_translations.item_id')
            ->where('item_translations.locale','=',config::get('app.locale'))
            ->select('items.id', 'items.is_active', 'items.is_approved', 'items.image',
                'item_translations.name','item_translations.short_description',
                'item_translations.long_description','item_translations.locale');

    }
}
