<?php

namespace App\Models;

use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;



class User extends Authenticatable
{
    const VERIFIED_USER ='1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER ='true';
    const REGULAR_USER = 'false';

    public $transformer = UserTransformer::class;

    // public $transformer = UserTransformer::class;
    // protected $table = 'users';
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

     public function products(){
        return $this->hasMany(products::class);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'verified',
        'verification_token',
        'admin',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
        'deleted_at',
        'email_verified_at',
        'headers'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setNAmeAttribute($name){
        $this->attributes['name'] = $name;
    }

     public function getNAmeAttribute($name){
        return ucwords($name);
    }

     public function setEmailAttribute($email){
        $this->attributes['email'] = strtolower($email) ;
    }

     public function isVerified(){
        return $this->verified ==User::VERIFIED_USER;
    }

    public function isAdmin(){
        return $this->admin ==User::ADMIN_USER;
    }

    public static function generateVerificationCode(){
        //return Str::random(40);
        return Str::random(40);
    }
}
