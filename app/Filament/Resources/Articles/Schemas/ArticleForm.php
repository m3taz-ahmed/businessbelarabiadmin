<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Images')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover_image')
                            ->collection('cover_image')
                            ->image()
                            ->imageEditor()
                            ->label('Cover Image')
                            ->preserveFilenames()
                            ->disablePreview(), // Disable client-side preview to reduce CORS issues

                        SpatieMediaLibraryFileUpload::make('main_image')
                            ->collection('main_image')
                            ->image()
                            ->imageEditor()
                            ->label('Main Image')
                            ->preserveFilenames()
                            ->disablePreview(), // Disable client-side preview to reduce CORS issues
                    ])
                    ->columns(2),

                Fieldset::make('Article Information')
                    ->schema([
                        Select::make('author_name')
                            ->relationship('author', 'id')
                            ->searchable()
                            ->preload()
                            ->label('Author'),

                        TextInput::make('sort')
                            ->numeric()
                            ->default(0)
                            ->label('Sort Order'),

                        DateTimePicker::make('schedule_publish_date')
                            ->label('Schedule Publish Date'),
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

                        Toggle::make('is_slider')
                            ->default(false)
                            ->label('Show in Slider'),
                    ])
                    ->columns(3),

                Fieldset::make('Categories & Tags')
                    ->schema([
                        Select::make('categories')
                            ->relationship('categories', 'id')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Categories'),

                        Select::make('tags')
                            ->relationship('tags', 'id')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Tags'),
                    ])
                    ->columns(2),

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
                                    ->label('Article Title'),

                                Textarea::make('desc')
                                    ->rows(3)
                                    ->label('Short Description'),
                            ])
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                    ]),
            ]);
    }
}
