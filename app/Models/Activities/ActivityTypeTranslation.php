<?php

namespace App\Models\Activities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTypeTranslation extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table ='activity_type_translations';
    protected $fillable=['name','local','activity_type_id'];
    protected $hidden=['local','activity_type_id','created_at', 'updated_at'];
}
