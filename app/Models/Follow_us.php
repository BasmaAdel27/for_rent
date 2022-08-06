<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow_us extends Model
{
    use HasFactory;
    protected $fillable=[
        'instagram','facebook','twitter','phone','email','logo'
        ];
}
