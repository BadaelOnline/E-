<?php

namespace App\Models\Admin\TransModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTranslation extends Model
{
    use HasFactory;
    protected $table='employee_translation';



    public function Employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
