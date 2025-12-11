<?php

namespace App\Filament\Resources\Podcasts\Components;

use Filament\Forms\Components\Field;

class DurationPicker extends Field
{
    protected string $view = 'filament.resources.podcasts.components.duration-picker';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(function ($state) {
            // Convert HH:MM:SS format to seconds
            if (preg_match('/^(\d+):([0-5]?[0-9]):([0-5][0-9])$/', $state, $matches)) {
                $hours = (int)$matches[1];
                $minutes = (int)$matches[2];
                $seconds = (int)$matches[3];
                return ($hours * 3600) + ($minutes * 60) + $seconds;
            }
            // If already in seconds format, keep as is
            elseif (is_numeric($state)) {
                return (int)$state;
            }
            return 0;
        });

        $this->formatStateUsing(function ($state) {
            // Convert seconds to HH:MM:SS format for display
            if (is_numeric($state) && $state > 0) {
                $hours = floor($state / 3600);
                $minutes = floor(($state % 3600) / 60);
                $seconds = $state % 60;
                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            }
            return '00:00:00';
        });
        
        $this->required(false); // Allow empty values
    }
}