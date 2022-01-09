<?php

namespace App\Models\Admin;

use App\Models\Admin\TransModel\EmployeeTranslation;
use App\Models\Stores_Orders\Stores_Order;
use App\Scopes\EmployeeScope;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $guard = 'employee-api';
    protected $table='employees';
    protected $fillable = [
        'first_name','last_name','age',
        'email','email_verified_at','password','location_id',
        'social_media_id','image','is_active','salary',
        'certificate','start_date',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
     *s
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
    protected static function booted()
    {
        parent::booted();
        static::addGlobalScope(new EmployeeScope);
    }
    public function EmployeeTranslation()
    {
        return $this->hasMany(EmployeeTranslation::class);
    }
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_employee',
            'employee_id',
            'role_id',
            'id',
            'id');
    }
    public function Stores_Order()
    {
        return $this->hasMany(Stores_Order::class);
    }
}
