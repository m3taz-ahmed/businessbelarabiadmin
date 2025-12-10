<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\UserCourse;
use Filament\Widgets\Widget;

class CourseEnrollmentStatsWidget extends Widget
{
    protected string $view = 'filament.widgets.course-enrollment-stats-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    public function getViewData(): array
    {
        return [
            'totalCourses' => Course::count(),
            'totalEnrollments' => UserCourse::count(),
            'popularCourse' => Course::withCount('users')->orderBy('users_count', 'desc')->first(),
        ];
    }
}