<div x-data="{
    hours: 0,
    minutes: 0,
    seconds: 0,
    init() {
        // Parse initial value if exists
        const value = @js($getState());
        if (value && value !== '00:00:00' && value.includes(':')) {
            const parts = value.split(':');
            this.hours = parseInt(parts[0]) || 0;
            this.minutes = parseInt(parts[1]) || 0;
            this.seconds = parseInt(parts[2]) || 0;
        } else if (value && !isNaN(parseInt(value)) && parseInt(value) > 0) {
            // Convert seconds to H:M:S
            const totalSeconds = parseInt(value);
            this.hours = Math.floor(totalSeconds / 3600);
            this.minutes = Math.floor((totalSeconds % 3600) / 60);
            this.seconds = totalSeconds % 60;
        }
    },
    updateValue() {
        const formatted = `${String(this.hours).padStart(2, '0')}:${String(this.minutes).padStart(2, '0')}:${String(this.seconds).padStart(2, '0')}`;
        $wire.$set(@js($getStatePath()), formatted);
    },
    incrementHours() {
        this.hours = (this.hours + 1) % 100;
        this.updateValue();
    },
    decrementHours() {
        this.hours = (this.hours - 1 + 100) % 100;
        this.updateValue();
    },
    incrementMinutes() {
        this.minutes = (this.minutes + 1) % 60;
        if (this.minutes === 0 && this.seconds === 0) {
            this.incrementHours();
        }
        this.updateValue();
    },
    decrementMinutes() {
        this.minutes = (this.minutes - 1 + 60) % 60;
        if (this.minutes === 59 && this.seconds === 0) {
            this.decrementHours();
        }
        this.updateValue();
    },
    incrementSeconds() {
        this.seconds = (this.seconds + 1) % 60;
        if (this.seconds === 0) {
            this.incrementMinutes();
        }
        this.updateValue();
    },
    decrementSeconds() {
        this.seconds = (this.seconds - 1 + 60) % 60;
        if (this.seconds === 59) {
            this.decrementMinutes();
        }
        this.updateValue();
    }
}">
    <div class="flex items-center space-x-2">
        <!-- Hours -->
        <div class="flex flex-col items-center">
            <button type="button" @click="incrementHours()" class="p-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
            <span x-text="String(hours).padStart(2, '0')" class="font-mono text-lg w-10 text-center"></span>
            <button type="button" @click="decrementHours()" class="p-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <span class="text-xs mt-1">Hours</span>
        </div>

        <span class="text-lg font-mono">:</span>

        <!-- Minutes -->
        <div class="flex flex-col items-center">
            <button type="button" @click="incrementMinutes()" class="p-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
            <span x-text="String(minutes).padStart(2, '0')" class="font-mono text-lg w-10 text-center"></span>
            <button type="button" @click="decrementMinutes()" class="p-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <span class="text-xs mt-1">Minutes</span>
        </div>

        <span class="text-lg font-mono">:</span>

        <!-- Seconds -->
        <div class="flex flex-col items-center">
            <button type="button" @click="incrementSeconds()" class="p-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
            <span x-text="String(seconds).padStart(2, '0')" class="font-mono text-lg w-10 text-center"></span>
            <button type="button" @click="decrementSeconds()" class="p-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <span class="text-xs mt-1">Seconds</span>
        </div>
    </div>
    
    <!-- Hidden input to store the actual value -->
    <input type="hidden" {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}" />
</div>