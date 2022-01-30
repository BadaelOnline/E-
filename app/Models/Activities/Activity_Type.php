<?php

namespace App\Models\Activities;

use App\Scopes\ActivityTypeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Activity_Type extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table ='activities_type';
    protected $fillable=['name','is_active','activity_id','created_at', 'updated_at'];
    protected $hidden=['is_active','created_at', 'updated_at'];
    protected $casts = ['activity_id' => 'integer'];
    public function getActivityIdAttribute($value)
    {
        switch ($value) {
            case "1":
                return Config('activities.activity.1');
                break;
            case "2":
                return Config('activities.activity.2');
                break;
            case "3":
                return Config('activities.activity.3');
                break;
            default:
                return Config('activities.activity.4');
        }
    }
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new ActivityTypeScope);
    }
}
