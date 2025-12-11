<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Fieldset;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('User Profile')
                    ->schema([
                        FileUpload::make('avatar')
                            ->image()
                            ->directory('users/avatars')
                            ->label('Avatar'),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Full Name'),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->label('Email'),

                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->label('Phone'),

                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->label('Password')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Fieldset::make('Personal Information')
                    ->schema([
                        Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ])
                            ->label('Gender'),

                        DatePicker::make('birthdate')
                            ->label('Birth Date'),

                        Select::make('language_id')
                            ->relationship('language', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Preferred Language'),
                    ])
                    ->columns(2),

                Fieldset::make('Account Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Active'),

                        Toggle::make('is_verified')
                            ->default(false)
                            ->label('Verified'),
                    ])
                    ->columns(2),
            ]);
    }
}
