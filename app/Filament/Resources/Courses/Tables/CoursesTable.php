<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->circular()
                    ->label('Image'),

                TextColumn::make('title')
                    ->searchable()
                    ->label('Course Title')
                    ->limit(50),

                TextColumn::make('level')
                    ->badge()
                    ->label('المستوى')
                    ->formatStateUsing(fn ($state): string => match ((int) $state) {
                        1 => 'مبتدئ',
                        2 => 'متوسط',
                        3 => 'متقدم',
                        default => 'غير محدد',
                    }),

                TextColumn::make('minutes')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} min" : '-'),

                TextColumn::make('category.trans.name')
                    ->badge()
                    ->label('Category'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                IconColumn::make('is_home')
                    ->boolean()
                    ->label('Home'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status'),

                TernaryFilter::make('is_home')
                    ->label('Show on Home'),

                SelectFilter::make('level')
                    ->options([
                        1 => 'مبتدئ',
                        2 => 'متوسط',
                        3 => 'متقدم',
                    ])
                    ->label('المستوى'),

                SelectFilter::make('category')
                    ->relationship('category', 'id')
                    ->label('Category')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->trans->first()?->name ?? 'N/A'),

                Filter::make('creation_date')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Created from'),
                        DatePicker::make('created_until')
                            ->label('Created until'),
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
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-globe-alt')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_published' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    BulkAction::make('unpublish')
                        ->label('Unpublish Selected')
                        ->icon('heroicon-o-lock-closed')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['is_published' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    BulkAction::make('feature')
                        ->label('Feature Selected')
                        ->icon('heroicon-o-star')
                        ->color('info')
                        ->action(fn ($records) => $records->each->update(['is_featured' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
