<?php

namespace App\Models\Admin\TransModel;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTranslation extends Model
{
    use HasFactory;
    protected $table='user_translation';
//    public function User()
//    {
//        return $this->belongsTo(User::class);
//    }
}
