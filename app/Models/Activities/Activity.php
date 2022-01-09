<?php

namespace App\Models\Activities;

use App\Models\Attachments\Attachment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table ='activities';
    protected $fillable=['name','created_at', 'updated_at'];
    protected $hidden=['created_at', 'updated_at'];
    public function Attachment()
    {
        return $this->hasMany(Attachment::class,'activity_id');
    }
    public function Activity_type()
    {
        return $this->hasMany(Activity_type::class,'activity_id');
    }

}
