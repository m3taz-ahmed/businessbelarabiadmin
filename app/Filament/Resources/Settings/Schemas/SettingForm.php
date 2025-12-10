<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Contact Information')
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->label('Email Address')
                            ->placeholder('contact@example.com'),

                        TextInput::make('mobile')
                            ->tel()
                            ->maxLength(255)
                            ->label('Mobile Number')
                            ->placeholder('+966 50 123 4567'),

                        TextInput::make('whatsapp')
                            ->tel()
                            ->maxLength(255)
                            ->label('WhatsApp Number')
                            ->placeholder('+966 50 123 4567'),
                    ])
                    ->columns(2),

                Fieldset::make('Social Media Links')
                    ->schema([
                        TextInput::make('facebook')
                            ->url()
                            ->maxLength(255)
                            ->label('Facebook')
                            ->placeholder('https://facebook.com/yourpage'),

                        TextInput::make('twitter')
                            ->url()
                            ->maxLength(255)
                            ->label('Twitter / X')
                            ->placeholder('https://twitter.com/yourhandle'),

                        TextInput::make('instagram')
                            ->url()
                            ->maxLength(255)
                            ->label('Instagram')
                            ->placeholder('https://instagram.com/yourhandle'),

                        TextInput::make('youtube')
                            ->url()
                            ->maxLength(255)
                            ->label('YouTube')
                            ->placeholder('https://youtube.com/yourchannel'),

                        TextInput::make('snapchat')
                            ->url()
                            ->maxLength(255)
                            ->label('Snapchat')
                            ->placeholder('https://snapchat.com/add/yourhandle'),

                        TextInput::make('linkedin')
                            ->url()
                            ->maxLength(255)
                            ->label('LinkedIn')
                            ->placeholder('https://linkedin.com/company/yourcompany'),

                        TextInput::make('telegram')
                            ->url()
                            ->maxLength(255)
                            ->label('Telegram')
                            ->placeholder('https://t.me/yourchannel'),

                        TextInput::make('tiktok')
                            ->url()
                            ->maxLength(255)
                            ->label('TikTok')
                            ->placeholder('https://tiktok.com/@yourhandle'),
                    ])
                    ->columns(2),
            ]);
    }
}
