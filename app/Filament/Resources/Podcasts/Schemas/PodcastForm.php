<?php

namespace App\Filament\Resources\Podcasts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;

class PodcastForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Podcast Media')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->image()
                            ->directory('podcasts')
                            ->label('Cover Image'),

                        FileUpload::make('sound_file')
                            ->acceptedFileTypes(['audio/*'])
                            ->directory('podcasts/audio')
                            ->label('Audio File'),
                    ])
                    ->columns(2),

                Fieldset::make('Podcast Information')
                    ->schema([
                        TextInput::make('uuid')
                            ->label('UUID')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('duration')
                            ->numeric()
                            ->label('Duration (seconds)'),
                    ])
                    ->columns(2),

                Fieldset::make('Visibility')
                    ->schema([
                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Active'),

                        Toggle::make('is_home')
                            ->default(false)
                            ->label('Show on Home'),
                    ])
                    ->columns(2),

                Fieldset::make('Categories')
                    ->schema([
                        Select::make('categories')
                            ->relationship('categories', 'id', fn ($query) => $query->with('trans'))
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->first()?->name ?? 'N/A')
                            ->label('Categories'),
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
                                    ->label('Podcast Name'),

                                Textarea::make('desc')
                                    ->rows(3)
                                    ->label('Description'),
                            ])
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
