<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'description',
    ];

    protected $casts = [
        'active' => 'boolean',
        'level' => 'integer',
        'minutes' => 'integer',
        'order' => 'integer',
    ];

    protected function getImageAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }
        
        // If it's already a full URL, return as is
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        
        // Use configured disk (public for local, s3 for production)
        try {
            return Storage::disk(config('filesystems.default'))->url($value);
        } catch (\Exception $e) {
            // Fallback to public disk if S3 is not configured
            return Storage::disk('public')->url($value);
        }
    }

    public function getLevelLabelAttribute(): ?string
    {
        $levels = [
            1 => 'مبتدئ',
            2 => 'متوسط',
            3 => 'متقدم',
        ];

        return $levels[$this->level] ?? null;
    }

    public function setLevelFromLabel($label): void
    {
        $levels = [
            'مبتدئ' => 1,
            'متوسط' => 2,
            'متقدم' => 3,
        ];

        $this->level = $levels[$label] ?? null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function requirements()
    {
        return $this->hasMany(CourseRequirement::class);
    }

    public function goals()
    {
        return $this->hasMany(CourseGoal::class);
    }

    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
    }

    public function contentItems()
    {
        return $this->hasMany(CourseContentItem::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_courses', 'course_id', 'user_id');
    }

    public function calculateUserCompletionPercentage($userId): float
    {
        $contentItems = $this->contentItems;
        $totalContentItems = $contentItems->count();

        if ($totalContentItems === 0) {
            return 0;
        }

        $completedContentItems = $contentItems->filter(function ($item) {
            return !is_null($item->ended_at);
        })->count();

        return round(($completedContentItems / $totalContentItems) * 100, 2);
    }
}
