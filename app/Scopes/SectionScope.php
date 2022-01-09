<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;


class SectionScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('section_translations', 'sections.id', '=','section_translations.section_id' )
            ->where('section_translations.local','=',Config::get('app.locale'))
            ->select(['sections.id','sections.image','sections.is_active',
                'section_translations.name']);
    }
}
