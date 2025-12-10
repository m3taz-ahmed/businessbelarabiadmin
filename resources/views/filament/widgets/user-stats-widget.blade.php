<x-filament-widgets::widget>
    <x-filament::section class="bg-white dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4" style="margin-bottom: 20px;">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">User Statistics</h3>
            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                Real-time
            </span>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 border border-blue-200 dark:border-blue-700 rounded-xl p-4 flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-user-group class="w-6 h-6 text-white inline-block" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $totalUsers }}</p>
                    <p class="text-sm text-blue-700 dark:text-blue-300">Total Users</p>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 border border-green-200 dark:border-green-700 rounded-xl p-4 flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-check-badge class="w-6 h-6 text-white inline-block" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $activeUsers }}</p>
                    <p class="text-sm text-green-700 dark:text-green-300">Active Users</p>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 border border-purple-200 dark:border-purple-700 rounded-xl p-4 flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-envelope class="w-6 h-6 text-white inline-block" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $verifiedUsers }}</p>
                    <p class="text-sm text-purple-700 dark:text-purple-300">Verified Users</p>
                </div>
            </div>
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Last updated:</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ now()->format('M j, Y g:i A') }}</span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>