<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContentItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'sort_order',
        'content_id',
        'content_type'
    ];
    
    /**
     * Get the course that owns this content item.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    /**
     * Get the owning content model (Articles, Podcast, etc.).
     */
    public function content()
    {
        return $this->morphTo();
    }
    
    /**
     * Get the user course content items for this content item.
     */
    public function userCourseContentItems()
    {
        return $this->hasMany(UserCourseContentItem::class, 'course_content_item_id');
    }
    
    /**
     * Check if this content item is completed for a specific user.
     * 
     * @param int $userId The ID of the user to check
     * @return bool True if the item is completed, false otherwise
     */
    public function isCompletedForUser($userId)
    {
        $userCourseContentItem = $this->userCourseContentItems()
            ->where('user_id', $userId)
            ->first();
            
        return $userCourseContentItem && !is_null($userCourseContentItem->ended_at);
    }
    
    /**
     * Check if this content item has been started for a specific user.
     * 
     * @param int $userId The ID of the user to check
     * @return bool True if the item has been started, false otherwise
     */
    public function isStartedForUser($userId)
    {
        $userCourseContentItem = $this->userCourseContentItems()
            ->where('user_id', $userId)
            ->first();
            
        return $userCourseContentItem && !is_null($userCourseContentItem->started_at);
    }
    
    /**
     * Scope a query to only include articles.
     */
    public function scopeArticles($query)
    {
        return $query->where('content_type', Articles::class);
    }
    
    /**
     * Scope a query to only include podcasts.
     */
    public function scopePodcasts($query)
    {
        return $query->where('content_type', Podcast::class);
    }
}