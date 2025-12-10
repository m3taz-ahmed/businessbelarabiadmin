<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Language;

class CourseRequirement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'description',
        'course_id',
        'active',
        'order'
    ];
    
    /**
     * Get the course that owns the requirement.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    

}
