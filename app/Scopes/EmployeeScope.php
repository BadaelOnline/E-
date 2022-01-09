<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;

class EmployeeScope implements Scope
{
    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {

        $builder->join('employee_translation',
            'employees.id',
            '=', 'employee_translation.employee_id')

            ->where('employee_translation.local',
                '=', Config::get('app.locale'))
            ->select([
                'employees.id','employee_translation.first_name',
                'employee_translation.last_name',
                'employee_translation.local','age','location_id',
                'social_media_id', 'employees.is_active',
                'image','employees.email', 'salary', 'certificate',
                'start_date','employees.password'

                ]);
    }
}
