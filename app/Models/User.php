<?php

namespace App\Models;

use App\Models\Admin\Role;
use App\Models\Comment\Comment;
use App\Models\Doctors\Patient;
use App\Models\Interaction\Interaction;
use App\Models\SocialMedia\SocialMedia;
use App\Models\Admin\TransModel\UserTranslation;
use App\Models\Admin\TypeUser;
use App\Models\Stores\Store;
use App\Models\Stores_Orders\Stores_Order;
use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'age',
        'location_id',
        'social_media_id',
        'image',
        'is_active',
        'email',
        'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'is_active',
        'pivot',
        'created_at',
        'updated_at'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
    public function UserTranslation()
    {
        return $this->hasMany(UserTranslation::class);
    }
    public function TypeUser(){
        return $this->belongsToMany(
            TypeUser::class,
            'user_types',
            'user_id',
            'type_id');
    }
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_user',
            'user_id',
            'role_id',
            'id',
            'id');
    }
    public function Stores_Order()
    {
        return $this->hasMany(Stores_Order::class);
    }
    public function Patient()
    {
        return $this->hasMany(Patient::class);
    }
    public function socialMedia()
    {
        return $this->hasMany(SocialMedia::class);
    }
    public function Comment()
    {
        return $this->hasMany(Comment::class);
    }
    public function Interaction()
    {
        return $this->hasOne(Interaction::class);
    }
    Public function Doctor()
    {
        return $this->hasOne(Doctor::class);
    }
    Public function Store()
    {
        return $this->belongsToMany(Store::class,
        'store_users',
        'user_id',
        'store_id');
    }
    Public function Owned()
    {
        return $this->hasMany(Store::class, 'owner_id');
    }
}