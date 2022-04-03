<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Scope;

class OfferScope implements Scope
{

    /**
     * @inheritDoc
     */
    public function apply (Builder $builder, Model $model)
    {
        $builder->join('offer_translations','offers.id','=','offer_translations.offer_id')
            ->where('offer_translations.locale','=',Config::get('app.locale'))
            ->select([
                'offers.id',
                'offers.user_email',
                'offers.offer_price',
                'offers.selling_quantity',
                'offers.started_at',
                'offers.ended_at',
                'offers.is_active',
                'offers.is_offer',
                'offer_translations.name',
                'offer_translations.short_desc',
                'offer_translations.long_desc'
            ]);
    }
}
