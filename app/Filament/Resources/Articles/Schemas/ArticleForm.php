<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Filament\Components\SectionBuilder;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Illuminate\Validation\Rule;

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

                Fieldset::make('Content Sections')
                    ->columnSpan('full')
                    ->schema([
                        Builder::make('content')
                            ->label('Content Sections')
                            ->blocks([
                                Block::make('paragraph_text')
                                    ->label('Paragraph')
                                    ->icon('heroicon-m-bars-3-bottom-left')
                                    ->schema([
                                        TextInput::make('paragraph_title')
                                            ->label('Paragraph Title'),
                                        Textarea::make('paragraph_text')
                                            ->label('Content')
                                            ->rows(5),
                                    ]),
                                
                                Block::make('heading')
                                    ->label('Heading')
                                    ->icon('heroicon-m-h1')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Heading Title')
                                            ->required(),
                                        Select::make('title_level')
                                            ->label('Size')
                                            ->options([
                                                'small' => 'Small (H4)',
                                                'medium' => 'Medium (H3)',
                                                'large' => 'Large (H2)',
                                            ])
                                            ->default('medium'),
                                    ]),

                                Block::make('images')
                                    ->label('Image Gallery')
                                    ->icon('heroicon-m-photo')
                                    ->schema([
                                        Repeater::make('images')
                                            ->label('Images')
                                            ->schema([
                                                TextInput::make('image')
                                                    ->label('Image URL')
                                                    ->url()
                                                    ->required(),
                                                TextInput::make('title')
                                                    ->label('Caption / Title'),
                                            ])
                                            ->grid(2)
                                            ->defaultItems(1),
                                    ]),

                                Block::make('list')
                                    ->label('List')
                                    ->icon('heroicon-m-list-bullet')
                                    ->schema([
                                        TextInput::make('list_title')
                                            ->label('List Title'),
                                        Select::make('list_type')
                                            ->label('List Style')
                                            ->options([
                                                'dotted' => 'Bullet Points',
                                                'numbered' => 'Numbered',
                                            ])
                                            ->default('dotted'),
                                        Repeater::make('list_items')
                                            ->label('Items')
                                            ->simple(
                                                TextInput::make('item')->required()
                                            ),
                                    ]),

                                Block::make('q_multiple_choice')
                                    ->label('Multiple Choice Question')
                                    ->icon('heroicon-m-question-mark-circle')
                                    ->schema([
                                        TextInput::make('question')
                                            ->label('Question')
                                            ->required(),
                                        TextInput::make('answer')
                                            ->label('Correct Answer Label')
                                            ->required(),
                                        Repeater::make('multiple_choice')
                                            ->label('Options')
                                            ->simple(
                                                TextInput::make('option')->required()
                                            ),
                                    ]),

                                Block::make('q_true_false')
                                    ->label('True/False Question')
                                    ->icon('heroicon-m-check-circle')
                                    ->schema([
                                        TextInput::make('question_true_false')
                                            ->label('Question')
                                            ->required(),
                                        Select::make('answer_true_false')
                                            ->label('Correct Answer')
                                            ->options([
                                                'true' => 'True',
                                                'false' => 'False',
                                            ])
                                            ->default('true'),
                                    ]),

                                Block::make('quote')
                                    ->label('Quote')
                                    ->icon('heroicon-m-chat-bubble-bottom-center-text')
                                    ->schema([
                                        Textarea::make('quote')
                                            ->label('Quote')
                                            ->rows(3)
                                            ->required(),
                                        TextInput::make('author_name')
                                            ->label('Author Name'),
                                        TextInput::make('author_sub_title')
                                            ->label('Title/Role'),
                                        TextInput::make('author_image')
                                            ->label('Author Image URL')
                                            ->url(),
                                    ]),

                                Block::make('video')
                                    ->label('Video')
                                    ->icon('heroicon-m-video-camera')
                                    ->schema([
                                        TextInput::make('video_title')
                                            ->label('Video Title'),
                                        TextInput::make('video_file')
                                            ->label('Video URL')
                                            ->url()
                                            ->required(),
                                        TextInput::make('poster')
                                            ->label('Poster Image URL')
                                            ->url(),
                                        TextInput::make('duration')
                                            ->label('Duration (Seconds)')
                                            ->numeric(),
                                    ]),
                            ])
                            ->columnSpan('full')
                            ->collapsible()
                            ->cloneable(),
                    ]),

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
                            ->columnSpan('full')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->minItems(1)
                            ->required(),
                    ]),
            ]);
    }
}