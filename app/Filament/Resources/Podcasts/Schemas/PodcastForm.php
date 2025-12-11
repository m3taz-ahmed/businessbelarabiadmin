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
use Filament\Forms\Components\TextInput\Mask;
use Filament\Support\RawJs;

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
                            ->label('Duration (seconds)')
                            ->mask(RawJs::make(<<<'JS'
                                /^\d+:\d{2}:\d{2}$/
                            JS))
                            ->hint('Format: HH:MM:SS')
                            ->helperText('Enter duration in hours:minutes:seconds format or seconds only')
                            ->rule('regex:/^(\d+:)?([0-5]?[0-9]):([0-5][0-9])$|^(\d+)$/')
                            ->beforeStateDehydrated(function (TextInput $component, $state) {
                                // Convert HH:MM:SS format to seconds
                                if (preg_match('/^(\d+):([0-5]?[0-9]):([0-5][0-9])$/', $state, $matches)) {
                                    $hours = (int)$matches[1];
                                    $minutes = (int)$matches[2];
                                    $seconds = (int)$matches[3];
                                    $component->state(($hours * 3600) + ($minutes * 60) + $seconds);
                                } 
                                // If already in seconds format, keep as is
                                elseif (is_numeric($state)) {
                                    $component->state((int)$state);
                                }
                            })
                            ->formatStateUsing(function ($state) {
                                // Convert seconds to HH:MM:SS format for display
                                if (is_numeric($state) && $state > 0) {
                                    $hours = floor($state / 3600);
                                    $minutes = floor(($state % 3600) / 60);
                                    $seconds = $state % 60;
                                    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                                }
                                return $state;
                            }),
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