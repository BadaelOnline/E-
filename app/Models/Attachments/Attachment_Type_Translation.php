<?php

namespace App\Models\Attachments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment_Type_Translation extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table ='attachments_type_translation';
    protected $fillable=[
       'name','local','attachment_type_id'
    ];
    protected $hidden=['created_at', 'updated_at'];
}
