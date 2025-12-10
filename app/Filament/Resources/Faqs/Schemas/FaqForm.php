<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('question')
                    ->required()
                    ->maxLength(500)
                    ->label('Question'),

                Textarea::make('answer')
                    ->required()
                    ->rows(5)
                    ->label('Answer'),

                TextInput::make('sort')
                    ->numeric()
                    ->default(0)
                    ->label('Sort Order'),

                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
            ]);
    }
}
