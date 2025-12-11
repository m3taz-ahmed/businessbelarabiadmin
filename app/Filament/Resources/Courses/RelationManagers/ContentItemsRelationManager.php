<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Models\Article;
use App\Models\Podcast;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Str;

class ContentItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'contentItems';

    protected static ?string $title = 'Course Content';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Fieldset::make('Content Item')
                    ->schema([
                        MorphToSelect::make('content')
                            ->required()
                            ->types([
                                MorphToSelect\Type::make(Article::class)
                                    ->titleAttribute('uuid')
                                    ->modifyOptionsQueryUsing(function ($query) {
                                        // Exclude articles that are already added to this course
                                        $existingContentIds = static::getOwnerRecord()->contentItems()
                                            ->where('content_type', Article::class)
                                            ->pluck('content_id')
                                            ->toArray();
                                        
                                        return $query->with(['trans' => fn ($q) => $q->where('local', 2)])
                                            ->whereNotIn('id', $existingContentIds);
                                    })
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->where('local', 2)->first()?->name ?? $record->trans->first()?->name ?? 'Untitled Article')
                                    ->label('Article'),
                                MorphToSelect\Type::make(Podcast::class)
                                    ->titleAttribute('uuid')
                                    ->modifyOptionsQueryUsing(function ($query) {
                                        // Exclude podcasts that are already added to this course
                                        $existingContentIds = static::getOwnerRecord()->contentItems()
                                            ->where('content_type', Podcast::class)
                                            ->pluck('content_id')
                                            ->toArray();
                                        
                                        return $query->with(['trans' => fn ($q) => $q->where('local', 2)])
                                            ->whereNotIn('id', $existingContentIds);
                                    })
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->where('local', 2)->first()?->name ?? $record->trans->first()?->name ?? 'Untitled Podcast')
                                    ->label('Podcast'),
                            ])
                            ->searchable()
                            ->preload()
                            ->label('Content Type & Item'),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->label('Sort Order')
                            ->helperText('Lower numbers appear first'),
                    ])
                    ->columns(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->sortable()
                    ->label('#')
                    ->width(60),

                TextColumn::make('content_type')
                    ->badge()
                    ->formatStateUsing(function (string $state): string {
                        return match ($state) {
                            'App\\Models\\Article' => 'Article',
                            'App\\Models\\Podcast' => 'Podcast',
                            default => class_basename($state),
                        };
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'App\\Models\\Article' => 'success',
                        'App\\Models\\Podcast' => 'warning',
                        default => 'gray',
                    })
                    ->label('Type'),

                TextColumn::make('content')
                    ->searchable()
                    ->limit(50)
                    ->label('Content Title')
                    ->formatStateUsing(function ($record) {
                        if (!$record->content) return 'N/A';
                        
                        // Check if it's an Article
                        if ($record->content instanceof Article) {
                            return $record->content->trans->where('local', 2)->first()?->name 
                                ?? $record->content->trans->first()?->name 
                                ?? 'Untitled Article';
                        }
                        
                        // Check if it's a Podcast
                        if ($record->content instanceof Podcast) {
                            return $record->content->trans->where('local', 2)->first()?->name 
                                ?? $record->content->trans->first()?->name 
                                ?? 'Untitled Podcast';
                        }
                        
                        return 'N/A';
                    })
                    ->description(function ($record) {
                        if (!$record->content) return null;
                        
                        // Check if it's an Article
                        if ($record->content instanceof Article) {
                            $desc = $record->content->trans->where('local', 2)->first()?->desc
                                ?? $record->content->trans->first()?->desc;
                            return $desc ? Str::limit($desc, 60) : null;
                        }
                        
                        // Check if it's a Podcast
                        if ($record->content instanceof Podcast) {
                            $desc = $record->content->trans->where('local', 2)->first()?->desc
                                ?? $record->content->trans->first()?->desc;
                            return $desc ? Str::limit($desc, 60) : null;
                        }
                        
                        return null;
                    }),

                IconColumn::make('content.is_active')
                    ->boolean()
                    ->label('Active')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Added At'),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                SelectFilter::make('content_type')
                    ->options([
                        'App\\Models\\Article' => 'Article',
                        'App\\Models\\Podcast' => 'Podcast',
                    ])
                    ->label('Content Type'),

                TernaryFilter::make('content.is_active')
                    ->label('Active Status')
                    ->queries(
                        true: fn ($query) => $query->whereHas('content', fn ($q) => $q->where('is_active', true)),
                        false: fn ($query) => $query->whereHas('content', fn ($q) => $q->where('is_active', false)),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Add Content to Course'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->content) {
                                    $record->content->update(['is_active' => true]);
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->content) {
                                    $record->content->update(['is_active' => false]);
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->emptyStateHeading('No content yet')
            ->emptyStateDescription('Add articles or podcasts to this course')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
