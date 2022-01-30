<?php

namespace App\Models\Attachments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment_Type extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table ='attachments_type';
    protected $fillable=[
        'is_active'
    ];
    protected $hidden=['created_at', 'updated_at'];
    public function Attachment()
    {
        return $this->belongsTo(Attachment::class,'attachments_type_id');
    }
}
