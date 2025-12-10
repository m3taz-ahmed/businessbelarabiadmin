<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourseContentItem extends Model
{
    use HasFactory;
    
    protected $table = 'user_course_content_items';
    
    protected $fillable = [
        'user_id',
        'course_content_item_id',
        'started_at',
        'ended_at'
    ];
    
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];
    
    /**
     * Get the user that owns this record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the course content item that owns this record.
     */
    public function courseContentItem()
    {
        return $this->belongsTo(CourseContentItem::class);
    }
}