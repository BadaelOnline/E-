<?php

namespace App\Models\Activities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity_Type extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table ='activities_type';
    protected $fillable=['activity_id','created_at', 'updated_at'];
    protected $hidden=['created_at', 'updated_at'];
    public function Activity()
    {
        return $this->belongsTo(Activity::class,'activity_id');
    }

}
