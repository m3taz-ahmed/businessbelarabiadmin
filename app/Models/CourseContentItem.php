<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CourseContentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'sort_order',
        'content_id',
        'content_type',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function content(): MorphTo
    {
        return $this->morphTo()->morphWith([
            Article::class => ['trans', 'author'],
            Podcast::class => ['trans'],
        ]);
    }

    public function userCourseContentItems()
    {
        return $this->hasMany(UserCourseContentItem::class, 'course_content_item_id');
    }

    public function isCompletedForUser($userId): bool
    {
        $userCourseContentItem = $this->userCourseContentItems()
            ->where('user_id', $userId)
            ->first();

        return $userCourseContentItem && !is_null($userCourseContentItem->ended_at);
    }

    public function isStartedForUser($userId): bool
    {
        $userCourseContentItem = $this->userCourseContentItems()
            ->where('user_id', $userId)
            ->first();

        return $userCourseContentItem && !is_null($userCourseContentItem->started_at);
    }

    public function scopeArticles($query)
    {
        return $query->where('content_type', Article::class);
    }

    public function scopePodcasts($query)
    {
        return $query->where('content_type', Podcast::class);
    }
}
