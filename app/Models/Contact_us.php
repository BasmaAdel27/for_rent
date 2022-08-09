<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact_us extends Model
{
    use HasFactory;

    protected $fillable=[
      'name','email','description','user_id','phone','url'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function contact_us_file(){
        return $this->hasMany(Contact_us_file::class,'contactus_id','id');
    }
}
