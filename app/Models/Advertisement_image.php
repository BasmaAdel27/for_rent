<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement_image extends Model
{
    use HasFactory;
    protected   $fillable = ['image_name',"video", "advertisement_id"];
    protected   $table = 'advertisements_images';

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
}
