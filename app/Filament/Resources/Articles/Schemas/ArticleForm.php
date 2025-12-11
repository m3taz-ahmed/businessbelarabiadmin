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
use Filament\Forms\Components\FileUpload;

/**
 * Article form schema configuration.
 *
 * @property Schema $schema
 */

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        /** @var Schema $schema */
        return $schema
            ->components([
                /** @var \Filament\Schemas\Components\Fieldset $fieldset */
                Fieldset::make('Images')
                    ->schema([
                        // FileUpload::make('cover_image')
                        //     ->image()
                        //     ->directory('articles/cover')
                        //     /** @var \Filament\Forms\Components\FileUpload $fileUpload */
                        //     ->label('Cover Image'),

                        FileUpload::make('cover_image')
                            ->label('Cover Image')
                            ->image()
                            ->s3Directory('articles/cover')
                            ->default(null),

                        FileUpload::make('main_image')
                            ->image()
                            ->directory('articles/main')
                            /** @var \Filament\Forms\Components\FileUpload $fileUpload */
                            ->label('Main Image'),
                    ])
                    ->columns(2),

                Fieldset::make('Article Information')
                    ->schema([
                        Select::make('author_name')
                            ->relationship('author', 'id', fn ($query) => $query->with('trans'))
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->first()?->name ?? 'N/A')
                            /** @var \Filament\Forms\Components\Select $select */
                            ->label('Author'),

                        TextInput::make('sort')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            /** @var \Filament\Forms\Components\TextInput $textInput */
                            ->label('Sort Order'),

                        DateTimePicker::make('schedule_publish_date')
                            /** @var \Filament\Forms\Components\DateTimePicker $dateTimePicker */
                            ->label('Schedule Publish Date'),
                    ])
                    ->columns(2),

                Fieldset::make('Visibility')
                    ->schema([
                        Toggle::make('is_active')
                            ->default(true)
                            /** @var \Filament\Forms\Components\Toggle $toggle */
                            ->label('Active'),

                        Toggle::make('is_home')
                            ->default(false)
                            /** @var \Filament\Forms\Components\Toggle $toggle */
                            ->label('Show on Home'),

                        Toggle::make('is_slider')
                            ->default(false)
                            /** @var \Filament\Forms\Components\Toggle $toggle */
                            ->label('Show in Slider'),
                    ])
                    ->columns(3),

                Fieldset::make('Categories & Tags')
                    ->schema([
                        Select::make('categories')
                            ->relationship('categories', 'id', fn ($query) => $query->with('trans'))
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->first()?->name ?? 'N/A')
                            /** @var \Filament\Forms\Components\Select $select */
                            ->label('Categories'),

                        Select::make('tags')
                            ->relationship('tags', 'id', fn ($query) => $query->with('trans'))
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->first()?->name ?? 'N/A')
                            /** @var \Filament\Forms\Components\Select $select */
                            ->label('Tags'),
                    ])
                    ->columns(2),

                Fieldset::make('Translations')
                    ->schema([
                        Repeater::make('trans')
                            ->relationship('trans')
                            /** @var \Filament\Forms\Components\Repeater $repeater */
                            ->schema([
                                Select::make('local')
                                    ->relationship('language', 'name')
                                    ->required()
                                    /** @var \Filament\Forms\Components\Select $select */
                                    ->label('Language'),

                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    /** @var \Filament\Forms\Components\TextInput $textInput */
                                    ->label('Article Title'),

                                Textarea::make('desc')
                                    ->rows(3)
                                    /** @var \Filament\Forms\Components\Textarea $textarea */
                                    ->label('Short Description'),
                            ])
                            ->defaultItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                    ]),
            ]);
    }
}
