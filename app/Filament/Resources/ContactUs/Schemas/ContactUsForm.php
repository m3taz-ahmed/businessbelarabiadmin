<?php

namespace App\Filament\Resources\ContactUs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;

class ContactUsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Contact Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Name'),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->label('Email'),

                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->label('Phone'),
                    ])
                    ->columns(2),

                Fieldset::make('Message')
                    ->schema([
                        Textarea::make('message')
                            ->required()
                            ->rows(5)
                            ->label('Message'),
                    ]),
            ]);
    }
}
