<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Fieldset;
use Illuminate\Validation\Rule;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Tag Information')
                    ->schema([
                        TextInput::make('sort')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->label('Sort Order'),

                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Active'),

                        Select::make('category_id')
                            ->relationship('category', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->where('local', 2)->first()?->name ?? $record->trans->first()?->name ?? 'Unnamed Category')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Category'),
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
                                    ->label('Tag Name'),
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
