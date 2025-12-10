<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSliderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_slider_id',
        'title',
    ];
}
