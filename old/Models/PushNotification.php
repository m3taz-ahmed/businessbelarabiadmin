<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_sent',
        'action',
        'image',
        'title',
        'route',
        'body',
        'user_id',
        'is_read',
    ];
}
