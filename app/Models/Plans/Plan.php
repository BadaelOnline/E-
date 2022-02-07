<?php

namespace App\Models\Plans;

use App\Scopes\ActivityTypeScope;
use App\Scopes\PlanScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Plan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'plans';
    protected $fillable = [
        'activity_id', 'num_of_month',
        'price', 'discount', 'is_active', 'features'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'features' => 'array',
    ];

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
        static::addGlobalScope(new PlanScope);
    }

    public function Subscription()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function getFeaturesAttribute($value)
    {
       return  $value = Config::get('activities.plan');
    }

}
