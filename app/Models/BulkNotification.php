<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'image',
        'action',
        'route',
        'sent_at',
        'is_sent',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'bulk_notification_users', 'bulk_notification_id', 'user_id');
    }
}
