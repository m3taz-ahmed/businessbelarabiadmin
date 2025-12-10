<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'user_id',
        'os_type', // 0 => android, 1 => IOS
    ];

    protected $casts = [
        'os_type' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
