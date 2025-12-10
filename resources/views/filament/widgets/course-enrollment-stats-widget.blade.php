<x-filament-widgets::widget>
    <x-filament::section class="bg-white dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Course Enrollment Statistics</h3>
            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-300">
                Live Data
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-teal-50 to-teal-100 dark:from-teal-900/30 dark:to-teal-800/30 border border-teal-200 dark:border-teal-700 rounded-xl p-4 flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-teal-500 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-academic-cap class="w-6 h-6 text-white inline-block" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-teal-900 dark:text-teal-100">{{ $totalCourses }}</p>
                    <p class="text-sm text-teal-700 dark:text-teal-300">Total Courses</p>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900/30 dark:to-cyan-800/30 border border-cyan-200 dark:border-cyan-700 rounded-xl p-4 flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-cyan-500 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-user-group class="w-6 h-6 text-white inline-block" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-cyan-900 dark:text-cyan-100">{{ $totalEnrollments }}</p>
                    <p class="text-sm text-cyan-700 dark:text-cyan-300">Total Enrollments</p>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/30 border border-emerald-200 dark:border-emerald-700 rounded-xl p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-12 h-12 bg-emerald-500 rounded-lg flex items-center justify-center">
                        <x-heroicon-o-fire class="w-6 h-6 text-white inline-block" />
                    </div>
                    <div class="ml-4">
                        <p class="text-lg font-bold text-emerald-900 dark:text-emerald-100 truncate">
                            {{ $popularCourse ? ($popularCourse->title ?? 'Untitled Course') : 'No data available' }}
                        </p>
                        <p class="text-sm text-emerald-700 dark:text-emerald-300 mt-1">
                            {{ $popularCourse?->users_count ?? 0 }} enrollments
                        </p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-emerald-200 dark:border-emerald-700">
                    <p class="text-xs text-emerald-600 dark:text-emerald-400">Most popular course</p>
                </div>
            </div>
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center text-gray-600 dark:text-gray-400">
                    <x-heroicon-o-arrow-trending-up class="w-4 h-4 mr-1 text-gray-500 dark:text-gray-400 inline-block" />
                    <span>Enrollment trend: +12% this month</span>
                </div>
                <x-filament::button color="gray" size="sm" onclick="window.location.href='{{ route('filament.admin.resources.courses.index') }}'">
                    Detailed Report
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>