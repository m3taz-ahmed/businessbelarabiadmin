<?php

namespace App\Filament\Widgets;

use App\Models\Notification;
use Filament\Widgets\Widget;

class NotificationsCounterWidget extends Widget
{
    protected string $view = 'filament.widgets.notifications-counter-widget';
    
    protected int | string | array $columnSpan = '1/2';
    
    public function getViewData(): array
    {
        return [
            'totalNotifications' => Notification::count(),
            'unreadNotifications' => Notification::where('is_seen', false)->count(),
            'sentNotifications' => Notification::where('is_sent', true)->count(),
        ];
    }
}