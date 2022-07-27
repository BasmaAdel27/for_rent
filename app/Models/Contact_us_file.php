<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact_us_file extends Model
{
    use HasFactory;
    public function contact_us(){
        return $this->belongsTo(Contact_us::class);
    }
}
