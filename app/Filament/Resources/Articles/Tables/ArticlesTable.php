<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Models\Tag;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->circular(),

                TextColumn::make('trans.name')
                    ->searchable()
                    ->label('Title')
                    ->limit(50)
                    ->wrap()
                    ->default('Untitled'),

                TextColumn::make('author.trans.name')
                    ->label('Author')
                    ->searchable()
                    ->default('N/A'),

                TextColumn::make('categories.trans.name')
                    ->badge()
                    ->label('Categories')
                    ->separator(',')
                    ->default('None'),

                TextColumn::make('tags.trans.name')
                    ->badge()
                    ->label('Tags')
                    ->separator(',')
                    ->color('success')
                    ->default('None'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                IconColumn::make('is_home')
                    ->boolean()
                    ->label('Home'),

                IconColumn::make('is_slider')
                    ->boolean()
                    ->label('Slider'),

                TextColumn::make('schedule_publish_date')
                    ->dateTime()
                    ->sortable()
                    ->label('Publish Date'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                TernaryFilter::make('is_active')
                    ->label('Active Status'),

                TernaryFilter::make('is_home')
                    ->label('Show on Home'),

                TernaryFilter::make('is_slider')
                    ->label('Show in Slider'),

                SelectFilter::make('author_name')
                    ->relationship('author', 'id')
                    ->label('Author')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->first()?->name ?? 'N/A'),

                SelectFilter::make('categories')
                    ->relationship('categories', 'id')
                    ->multiple()
                    ->label('Category')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->first()?->name ?? 'N/A'),

                SelectFilter::make('tags')
                    ->relationship('tags', 'id')
                    ->multiple()
                    ->label('Tags')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->first()?->name ?? 'N/A'),

                Filter::make('publish_date')
                    ->form([
                        DatePicker::make('published_from')
                            ->placeholder('Published from'),
                        DatePicker::make('published_until')
                            ->placeholder('Published until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['published_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('schedule_publish_date', '>=', $date),
                            )
                            ->when(
                                $data['published_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('schedule_publish_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['published_from'] ?? null) {
                            $indicators['published_from'] = 'Published from ' . Carbon::parse($data['published_from'])->toFormattedDateString();
                        }

                        if ($data['published_until'] ?? null) {
                            $indicators['published_until'] = 'Published until ' . Carbon::parse($data['published_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->placeholder('Created from'),
                        DatePicker::make('created_until')
                            ->placeholder('Created until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->defaultSort('schedule_publish_date', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    BulkAction::make('add_to_home')
                        ->label('Add to Home')
                        ->icon('heroicon-o-home')
                        ->color('info')
                        ->action(fn ($records) => $records->each->update(['is_home' => true]))
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('add_to_slider')
                        ->label('Add to Slider')
                        ->icon('heroicon-o-photo')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['is_slider' => true]))
                        ->deselectRecordsAfterCompletion(),
                    
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}