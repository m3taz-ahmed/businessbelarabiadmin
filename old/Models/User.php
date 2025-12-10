<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\DeviceToken;
use App\Models\Course;
use App\Models\UserCourse;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;

    public $guard = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password)
    {
        if ($password) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    function getAvatarAttribute($value){
        if ($value)
            return \Storage::disk('s3')->url($value);
        else
            return null;
    }

    public function setImageAttribute($image)
    {
        // If the image is a URL (e.g., from Google or Apple), store it directly
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            $this->attributes['image'] = $image; // Store the external URL
            
        } 
        // If the image is a file (e.g., uploaded via a form), upload it to S3
        elseif ($image) {
            $this->attributes['image'] = \Storage::disk('s3')->put('businessblarabi/users/images', $image);
        }
    }

    public function getImageAttribute($value)
    {
       
        if (!$value) {
            return null;
        }
    
        // If it's an external URL (e.g., from Google or Apple), return it as-is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value; // Return the external URL as-is
        }
        // Otherwise, assume it's an S3 file path and generate the full URL
        return \Storage::disk('s3')->url($value);
    }


    public function generaOtp()
    {
        $this->timestamps = false;
        $this->otp = rand(2000, 9999);
        $this->otp_exp = now()->addMinutes(10);
        $this->save();
    }
    public function restOtp()
    {
        $this->timestamps = false;
        $this->otp = null;
        $this->otp_exp = null;
        $this->save();
    }

    public function getRoleAttribute()
    {
        return $this->roles?->first()?->name ?? null;
    }

    public function category_interests(){
        return $this->belongsToMany(Category::class , 'user_categories_interests' , 'user_id' , 'category_id');
    }

    public function tag_interests(){
        return $this->belongsToMany(Tags::class , 'user_tags_interests' , 'user_id' , 'tag_id');
    }

    function likes()
    {
        return $this->morphMany(Like::class , 'likeable');
    }

    function fcmToken(){
        return $this->hasOne(DeviceToken::class);
    }
    
    /**
     * Get all of the user's snippets.
     */
    public function snippets()
    {
        return $this->hasMany(Snippet::class);
    }
    
    /**
     * Get all courses that the user is enrolled in.
     */
    public function userCourses()
    {
        return $this->hasMany(UserCourse::class);
    }
    
    /**
     * Get all courses that the user is enrolled in with course details.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'users_courses', 'user_id', 'course_id');
    }
    
    /**
     * Get all user course content items for this user.
     */
    public function userCourseContentItems()
    {
        return $this->hasMany(UserCourseContentItem::class);
    }
    
    /**
     * Calculate the completion percentage for a user's course based on completed content items.
     * 
     * @param int $courseId The ID of the course to calculate completion for
     * @return float The completion percentage (0-100)
     */
    public function calculateCourseCompletionPercentage($courseId)
    {
        $service = new \App\Services\CourseCompletionService();
        return $service->calculateUserCourseCompletion($this, $courseId);
    }
    
    /**
     * Calculate completion percentages for all user courses.
     * 
     * @return array Associative array with course_id as key and completion percentage as value
     */
    public function calculateAllCoursesCompletionPercentages()
    {
        $service = new \App\Services\CourseCompletionService();
        return $service->calculateAllUserCoursesCompletion($this);
    }
    
    /**
     * Get detailed completion information for a user's course.
     * 
     * @param int $courseId The ID of the course to get completion details for
     * @return array Detailed completion information
     */
    public function getCourseCompletionDetails($courseId)
    {
        $service = new \App\Services\CourseCompletionService();
        return $service->getUserCourseCompletionDetails($this, $courseId);
    }
}
