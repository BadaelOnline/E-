<?php

namespace App\Models\Plans;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanTranslation extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table ='plan_translations';
    protected $fillable=['name','local','plan_id'];
    protected $hidden=['created_at', 'updated_at'];
}
