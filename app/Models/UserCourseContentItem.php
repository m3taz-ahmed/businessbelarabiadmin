<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourseContentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_content_item_id',
        'started_at',
        'ended_at',
        'progress',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'progress' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courseContentItem()
    {
        return $this->belongsTo(CourseContentItem::class);
    }
}
