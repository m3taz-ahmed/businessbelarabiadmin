<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'mobile',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'snapchat',
        'whatsapp',
        'linkedin',
        'telegram',
        'tiktok',
    ];
}
