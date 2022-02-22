<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class AttacmentTypeScope implements \Illuminate\Database\Eloquent\Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('attachments_type_translation',
            'attachments_type.id', '=',
            'attachments_type_translation.attachment_type_id')
            ->where('attachments_type_translation.local',
                '=', Config::get('app.locale'))
            ->select([
                'attachments_type.id',
                'attachments_type_translation.name']);
    }

}