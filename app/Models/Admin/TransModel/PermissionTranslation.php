<?php

namespace App\Models\Admin\TransModel;


use App\Models\Admin\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionTranslation extends Model
{
    use HasFactory;
    protected $table='permission_translation';
    protected $fillable=['id','permission_id','local','name','description','display_name'];

    public function Permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
