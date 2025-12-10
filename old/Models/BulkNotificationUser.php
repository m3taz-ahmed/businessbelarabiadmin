<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkNotificationUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulk_id',
        'user_id',
        'is_read',
    ];

    public function bulkNotification()
    {
        return $this->belongsTo(BulkNotification::class, 'bulk_id');
    }
}
