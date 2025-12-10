<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;

class UserStatsWidget extends Widget
{
    protected string $view = 'filament.widgets.user-stats-widget';
    
    protected int | string | array $columnSpan = '1/2';
    
    public function getViewData(): array
    {
        return [
            'totalUsers' => User::count(),
            'activeUsers' => User::where('is_active', true)->count(),
            'verifiedUsers' => User::whereNotNull('email_verified_at')->count(),
        ];
    }
}