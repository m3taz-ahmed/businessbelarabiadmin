<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'image',
        'address',
        'company_name',
        'job',
        'city',
        'country',
        'is_active',
        'phone_number',
        'avatar',
        'firebase_uid',
        'email_verified_at',
        'firebase_provider',
        'gender',
        'birthdate',
        'receive_articles_notifications',
        'receive_podcasts_notifications',
        'receive_snippets_notifications',
        'otp',
        'otp_exp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
            'otp_exp' => 'datetime',
            'is_active' => 'boolean',
            'receive_articles_notifications' => 'boolean',
            'receive_podcasts_notifications' => 'boolean',
            'receive_snippets_notifications' => 'boolean',
        ];
    }

    protected function setPasswordAttribute($password): void
    {
        if ($password) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    protected function getAvatarAttribute($value): ?string
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

    protected function setImageAttribute($image): void
    {
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            $this->attributes['image'] = $image;
        } elseif ($image) {
            try {
                $this->attributes['image'] = Storage::disk(config('filesystems.default'))->put('users/images', $image);
            } catch (\Exception $e) {
                $this->attributes['image'] = Storage::disk('public')->put('users/images', $image);
            }
        }
    }

    protected function getImageAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        try {
            return Storage::disk(config('filesystems.default'))->url($value);
        } catch (\Exception $e) {
            return Storage::disk('public')->url($value);
        }
    }

    public function generateOtp(): void
    {
        $this->timestamps = false;
        $this->otp = rand(2000, 9999);
        $this->otp_exp = now()->addMinutes(10);
        $this->save();
    }

    public function resetOtp(): void
    {
        $this->timestamps = false;
        $this->otp = null;
        $this->otp_exp = null;
        $this->save();
    }

    public function getRoleAttribute(): ?string
    {
        return $this->roles?->first()?->name ?? null;
    }

    public function categoryInterests()
    {
        return $this->belongsToMany(Category::class, 'user_categories_interests', 'user_id', 'category_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    public function tagInterests()
    {
        return $this->belongsToMany(Tag::class, 'user_tags_interests', 'user_id', 'tag_id');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function fcmToken()
    {
        return $this->hasOne(DeviceToken::class);
    }

    public function snippets()
    {
        return $this->hasMany(Snippet::class);
    }

    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'users_courses', 'user_id', 'course_id');
    }

    public function userCourseContentItems()
    {
        return $this->hasMany(UserCourseContentItem::class);
    }

    public function calculateCourseCompletionPercentage($courseId): float
    {
        $course = Course::find($courseId);
        if (!$course) {
            return 0;
        }
        return $course->calculateUserCompletionPercentage($this->id);
    }

    public function calculateAllCoursesCompletionPercentages(): array
    {
        $completions = [];
        foreach ($this->courses as $course) {
            $completions[$course->id] = $course->calculateUserCompletionPercentage($this->id);
        }
        return $completions;
    }

    public function getCourseCompletionDetails($courseId): array
    {
        $course = Course::with('contentItems')->find($courseId);
        if (!$course) {
            return [];
        }

        $totalItems = $course->contentItems->count();
        $completedItems = $course->contentItems->filter(function ($item) {
            return $item->isCompletedForUser($this->id);
        })->count();

        return [
            'course_id' => $courseId,
            'total_items' => $totalItems,
            'completed_items' => $completedItems,
            'completion_percentage' => $totalItems > 0 ? round(($completedItems / $totalItems) * 100, 2) : 0,
        ];
    }
}
