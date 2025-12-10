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
        'snippet_data'
    ];
    
    protected $casts = [
        'notification_datetime' => 'datetime',
        'is_notified' => 'integer'
    ];
    
    /**
     * Get the user that owns the snippet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the parent content model (article or podcast).
     */
    public function content()
    {
        return $this->morphTo('content', 'content_type', 'content_id');
    }
    
    /**
     * Get article content if type is article.
     */
    public function article()
    {
        return $this->belongsTo(Articles::class, 'content_id')->where('content_type', 'article');
    }
    
    /**
     * Get podcast content if type is podcast.
     */
    public function podcast()
    {
        return $this->belongsTo(Podcast::class, 'content_id')->where('content_type', 'podcast');
    }
}
