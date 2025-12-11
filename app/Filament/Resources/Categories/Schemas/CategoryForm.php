<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Fieldset;
use Illuminate\Validation\Rule;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Category Information')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->directory('categories')
                            ->label('Category Image'),

                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->label('Display Order'),

                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Active'),

                        Toggle::make('is_home')
                            ->default(false)
                            ->label('Show on Home'),

                        Toggle::make('show_in_tab')
                            ->default(false)
                            ->label('Show in Tab'),
                    ]),

                Fieldset::make('Translations')
                    ->schema([
                        Repeater::make('trans')
                            ->relationship('trans')
                            ->schema([
                                Select::make('local')
                                    ->relationship('language', 'name')
                                    ->required()
                                    ->label('Language'),

                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Category Name'),

                                TextInput::make('desc')
                                    ->maxLength(500)
                                    ->label('Description'),
                            ])
                            ->defaultItems(1)
                            ->columnSpan('full')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->minItems(1)
                            ->required(),
                    ]),
            ]);
    }
}
