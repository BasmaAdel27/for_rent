<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymentmethod extends Model
{
    use HasFactory;
    protected $fillable=[
        'advertisement_id','user_id','owner_id','amount'
    ];
    public function owner(){
        return $this->belongsTo(User::class,'owner_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function advertisement(){
        return $this->belongsTo(Advertisement::class,'advertisement_id','id');
    }
}
