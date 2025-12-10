<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Models\Admin;

// Import all resources
use App\Filament\Resources\Admins\AdminResource;
use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Authors\AuthorResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Cities\CityResource;
use App\Filament\Resources\ContactUs\ContactUsResource;
use App\Filament\Resources\Courses\CourseResource;
use App\Filament\Resources\Faqs\FaqResource;
use App\Filament\Resources\Languages\LanguageResource;
use App\Filament\Resources\Notifications\NotificationResource;
use App\Filament\Resources\Podcasts\PodcastResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Settings\SettingResource;
use App\Filament\Resources\Tags\TagResource;
use App\Filament\Resources\Users\UserResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->authGuard('admin')
            ->authPasswordBroker('admins')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([
                AdminResource::class,
                ArticleResource::class,
                AuthorResource::class,
                CategoryResource::class,
                CityResource::class,
                ContactUsResource::class,
                CourseResource::class,
                FaqResource::class,
                LanguageResource::class,
                NotificationResource::class,
                PodcastResource::class,
                RoleResource::class,
                SettingResource::class,
                TagResource::class,
                UserResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // Removed default widgets: AccountWidget and FilamentInfoWidget
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // Enable dark mode
            ->darkMode(true)
            // Set custom brand name
            ->brandName('BusinessBelarabi')
            // Set custom logo with larger size
            ->brandLogo(fn () => view('filament.brand-logo'))
            // Set dark mode logo (optional)
            ->darkModeBrandLogo(fn () => view('filament.brand-logo'))
            // Add custom CSS for proper Tailwind styling
            ->renderHook(
                'panels::head.end',
                fn () => '<link rel="stylesheet" href="' . asset('css/filament-custom.css') . '">'
            );
    }
}