<?php

namespace App\Models\Attachments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table ='attachments';
    protected $fillable=[
        'path','activity_id','record_num',
        'attachments_type_id','created_at', 'updated_at'
    ];
    protected $hidden=['created_at', 'updated_at'];
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
    public function Attachment_Type()
    {
        return $this->belongsTo(Attachment_Type::class,'attachments_type_id');
    }
}
