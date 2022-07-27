<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourit extends Model
{
    use HasFactory;
    public function user(){
        return $this->belongsTo(user::class );
    }

    public function advertisement(){
        return $this->belongsTo(Advertisement::class );
    }
}
