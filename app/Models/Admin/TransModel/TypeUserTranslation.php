<?php

namespace App\Models\Admin\TransModel;

use App\Models\Admin\TypeUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUserTranslation extends Model
{
    use HasFactory;
    protected $table='type_users_translation';

    public function TypeUser()
    {
        return $this->belongsTo(TypeUser::class);
    }
}
