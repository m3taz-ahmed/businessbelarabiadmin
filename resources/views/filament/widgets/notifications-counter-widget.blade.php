<x-filament-widgets::widget>
    <x-filament::section class="bg-white dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4" style="margin-bottom: 10px;">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Notifications</h3>
            <x-filament::button color="gray" size="sm" icon="heroicon-m-bell" onclick="window.location.href='{{ route('filament.admin.resources.notifications.index') }}'">
                View All
            </x-filament::button>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/30 dark:to-indigo-800/30 border border-indigo-200 dark:border-indigo-700 rounded-xl p-4 flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-bell class="w-6 h-6 text-white inline-block" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-indigo-900 dark:text-indigo-100">{{ $totalNotifications }}</p>
                    <p class="text-sm text-indigo-700 dark:text-indigo-300">Total</p>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-800/30 border border-amber-200 dark:border-amber-700 rounded-xl p-4 flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-amber-500 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-envelope-open class="w-6 h-6 text-white inline-block" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-amber-900 dark:text-amber-100">{{ $unreadNotifications }}</p>
                    <p class="text-sm text-amber-700 dark:text-amber-300">Unread</p>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 border border-green-200 dark:border-green-700 rounded-xl p-4 flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-paper-airplane class="w-6 h-6 text-white inline-block" />
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $sentNotifications }}</p>
                    <p class="text-sm text-green-700 dark:text-green-300">Sent</p>
                </div>
            </div>
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                {{-- <x-heroicon-o-information-circle class="w-3 h-3 mr-1 text-gray-500 dark:text-gray-400 inline-block" /> --}}
                <span>Real-time notification monitoring</span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>