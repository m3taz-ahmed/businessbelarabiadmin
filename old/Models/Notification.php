<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'is_sent',
        'is_fom_admin',
        'is_seen',
        'is_push_notification',
        'action',
        'image',
        'title',
        'route',
        'body',
        'bulk_id',
        'user_id',
    ];
}
