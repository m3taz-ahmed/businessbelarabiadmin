<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content_type',
        'content_id',
        'title',
        'notification_datetime',
        'is_notified',
        'snippet_data',
    ];

    protected $casts = [
        'notification_datetime' => 'datetime',
        'is_notified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function content()
    {
        return $this->morphTo('content', 'content_type', 'content_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'content_id')->where('content_type', 'article');
    }

    public function podcast()
    {
        return $this->belongsTo(Podcast::class, 'content_id')->where('content_type', 'podcast');
    }
}
