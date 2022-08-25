<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title','description','price','bedroom_num','bathroom_num','beds_num','level','image','furniture','type','status','area', 'address', 'city_id'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function advertisement_image(){
        return $this ->hasMany(Advertisement_image::class , 'advertisement_id','id' );
    }

    public function comment(){
        return $this ->hasMany(Comment::class , 'advertisement_id','id' );
    }

    public function favourit(){
        return $this->hasMany(Favourit::class , 'advertisement_id','id');
    }

    public function ratings(){
        return $this->hasMany(Rating::class,'advertisement_id','id');
    }

    public function notification(){
        return $this->hasMany(Notification::class , 'advertisement_id','id');
    }


    public function city(){
        return $this->belongsTo(City::class);
    }
    public function payment(){
    return $this->hasMany(Paymentmethod::class,'advertisement_id','id');
    }

}
