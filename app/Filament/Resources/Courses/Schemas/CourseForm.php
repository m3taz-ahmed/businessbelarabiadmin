<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Course Information')
                    ->schema([
                        TextInput::make('uuid')
                            ->label('UUID')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->label('Sort Order'),

                        TextInput::make('level')
                            ->maxLength(50)
                            ->label('Level (Beginner/Intermediate/Advanced)'),

                        TextInput::make('minutes')
                            ->numeric()
                            ->label('Total Duration (minutes)'),
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

                Fieldset::make('Category')
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'id')
                            ->preload()
                            ->searchable()
                            ->label('Category'),
                    ]),

                Fieldset::make('Course Content')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Course Title'),

                        Textarea::make('description')
                            ->rows(3)
                            ->label('Description'),
                    ])
                    ->columns(1),
            ]);
    }
}
