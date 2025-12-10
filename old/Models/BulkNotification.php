<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'is_to_employees',
        // 'is_to_clients',
        'is_push',
        // 'is_to_providers',
        'is_sent',
        'title',
        'body',
        'image',
        'route',
        'send_at'
    ];

    public function getImageAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);

        else
            return null;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'bulk_notification_users','bulk_id','user_id');
    }
}
