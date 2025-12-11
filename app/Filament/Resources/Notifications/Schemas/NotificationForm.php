<?php

namespace App\Filament\Resources\Notifications\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Fieldset;

class NotificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Notification Content')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Title'),

                        Textarea::make('body')
                            ->required()
                            ->rows(3)
                            ->label('Body'),

                        FileUpload::make('image')
                            ->image()
                            ->directory('notifications')
                            ->label('Image'),
                    ])
                    ->columns(2),

                Fieldset::make('Settings')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name', fn ($query) => $query->orderBy('name'))
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name ?? $record->email ?? 'Unknown User')
                            ->label('User'),

                        TextInput::make('route')
                            ->maxLength(255)
                            ->label('Route'),

                        TextInput::make('action')
                            ->maxLength(255)
                            ->label('Action'),
                    ])
                    ->columns(2),

                Fieldset::make('Status')
                    ->schema([
                        Toggle::make('is_sent')
                            ->default(false)
                            ->label('Sent'),

                        Toggle::make('is_fom_admin')
                            ->default(true)
                            ->label('From Admin'),

                        Toggle::make('is_seen')
                            ->default(false)
                            ->label('Seen'),

                        Toggle::make('is_push_notification')
                            ->default(true)
                            ->label('Push Notification'),
                    ])
                    ->columns(4),
            ]);
    }
}
