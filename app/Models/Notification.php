<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

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

    protected $casts = [
        'is_sent' => 'boolean',
        'is_fom_admin' => 'boolean',
        'is_seen' => 'boolean',
        'is_push_notification' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
