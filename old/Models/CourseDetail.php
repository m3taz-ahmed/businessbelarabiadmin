<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Language;

class CourseDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'local',
        'title',
        'description'
    ];
    
    /**
     * Get the course that owns the detail.
     */
    public function course()
    {
        // return $this->belongsTo(Course::class);
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
    
    /**
     * Get the language for the detail.
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'local');
    }
}
