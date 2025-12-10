<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\CourseRequirement;
use App\Models\CourseGoal;
use App\Models\UserCourse;
use App\Models\CourseContentItem;

class Course extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'image',
        'category_id',
        'level',
        'minutes',
        'active',
        'order',
        'title',
        'description'
    ];
    
    // Accessor to get the Arabic label for the level
    public function getLevelLabelAttribute()
    {
        $levels = [
            1 => 'مبتدئ',
            2 => 'متوسط',
            3 => 'متقدم'
        ];
        
        return $levels[$this->level] ?? null;
    }
    
    // Mutator to set the level based on Arabic label
    public function setLevelFromLabel($label)
    {
        $levels = [
            'مبتدئ' => 1,
            'متوسط' => 2,
            'متقدم' => 3
        ];
        
        $this->level = $levels[$label] ?? null;
    }

    public function getImageAttribute($value)
    {
        if ($value)
            return \Storage::disk('s3')->url($value);
        else
            return null;
    }
    
    /**
     * Get the category that owns the course.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * Get the requirements for the course.
     */
    public function requirements()
    {
        return $this->hasMany(CourseRequirement::class);
    }
    
    /**
     * Get the goals for the course.
     */
    public function goals()
    {
        return $this->hasMany(CourseGoal::class);
    }
    
    /**
     * Get the users who have enrolled in this course.
     */
    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
    }
    
    /**
     * Get the content items for this course.
     */
    public function contentItems()
    {
        return $this->hasMany(CourseContentItem::class);
    }
    
    /**
     * Calculate the completion percentage for a specific user in this course.
     * 
     * @param int $userId The ID of the user to calculate completion for
     * @return float The completion percentage (0-100)
     */
    public function calculateUserCompletionPercentage($userId)
    {
        // Get content items with their user-specific data
        $contentItems = $this->contentItems;
        
        // Get total content items in the course
        $totalContentItems = $contentItems->count();
        
        if ($totalContentItems === 0) {
            return 0;
        }
        
        // Count completed content items (those with ended_at not null)
        $completedContentItems = $contentItems->filter(function ($item) {
            return !is_null($item->ended_at);
        })->count();
        
        // Calculate percentage
        return round(($completedContentItems / $totalContentItems) * 100, 2);
    }
}