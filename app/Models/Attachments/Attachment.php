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
    public function Attachment_Type()
    {
        return $this->hasMany(Attachment_Type::class,'attachments_type_id');
    }

}
