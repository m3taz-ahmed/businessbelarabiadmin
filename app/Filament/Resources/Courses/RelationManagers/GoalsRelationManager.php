<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\Textarea;
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
use Filament\Tables\Filters\TernaryFilter;

class GoalsRelationManager extends RelationManager
{
    protected static string $relationship = 'goals';

    protected static ?string $title = 'Course Goals';

    protected static ?string $recordTitleAttribute = 'description';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Fieldset::make('Goal Information')
                    ->schema([
                        Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->label('Goal Description')
                            ->placeholder('What will students achieve?'),

                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->label('Display Order')
                            ->helperText('Lower numbers appear first'),
                    ])
                    ->columns(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('order')
                    ->sortable()
                    ->label('#')
                    ->width(60),

                TextColumn::make('description')
                    ->searchable()
                    ->label('Goal Description')
                    ->wrap()
                    ->description(fn ($record) => $record->description ? 'Order: ' . $record->order : null),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created At'),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                TernaryFilter::make('description')
                    ->label('Has Description')
                    ->placeholder('All')
                    ->trueLabel('With Description')
                    ->falseLabel('Without Description')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('description')->where('description', '!=', ''),
                        false: fn ($query) => $query->whereNull('description')->orWhere('description', '=', ''),
                        blank: fn ($query) => $query
                    ),
            ])
            ->headerActions([
                CreateAction::make()
                    ->icon('heroicon-o-plus'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('reorder_up')
                        ->label('Move Up')
                        ->icon('heroicon-o-arrow-up')
                        ->color('info')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['order' => max(0, $record->order - 1)]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('reorder_down')
                        ->label('Move Down')
                        ->icon('heroicon-o-arrow-down')
                        ->color('info')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['order' => $record->order + 1]);
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    
                    DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->emptyStateHeading('No goals yet')
            ->emptyStateDescription('Add the first goal for this course')
            ->emptyStateIcon('heroicon-o-flag');
    }
}
