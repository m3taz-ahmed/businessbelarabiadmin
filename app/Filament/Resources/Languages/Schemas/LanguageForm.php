<?php

namespace App\Filament\Resources\Languages\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class LanguageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Language Name'),

                TextInput::make('code')
                    ->required()
                    ->maxLength(10)
                    ->label('Language Code')
                    ->helperText('e.g., en, ar, fr'),

                Select::make('direction')
                    ->required()
                    ->options([
                        'ltr' => 'Left to Right (LTR)',
                        'rtl' => 'Right to Left (RTL)',
                    ])
                    ->default('ltr')
                    ->label('Text Direction'),

                Toggle::make('is_active')
                    ->required()
                    ->default(true)
                    ->label('Active'),
            ]);
    }
}
