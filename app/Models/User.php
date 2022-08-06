<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name','email','password','type','status','phone','gender','image'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function verify(){
        return $this->hasOne(Verify::class);
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function advertisement(){
       return $this->hasMany(Advertisement::class,'user_id','id');
   }

    public function comment(){
        return $this->hasMany(Comment::class,'user_id','id');
    }
    public function contact_us(){
        return $this->hasMany(Contact_us::class , 'user_id','id');
    }
    public function favourit(){
        return $this->hasMany(Favourit::class , 'user_id','id');
    }

    public function rating(){
        return $this->hasMany(Rating::class , 'user_id','id');
    }
    public function notification(){
        return $this->hasMany(Notification::class , 'user_id','id');
    }

}
