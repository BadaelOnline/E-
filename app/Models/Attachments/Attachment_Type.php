<?php

namespace App\Models\Attachments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\AttacmentTypeScope;


class Attachment_Type extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table ='attachments_type';
    protected $fillable=[
        'is_active'
    ];
    protected $hidden=['created_at', 'updated_at'];
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new AttacmentTypeScope);
    }
    public function Attachment()
    {
        return $this->hasMany(Attachment::class,'attachments_type_id');
    }
}
