<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular(),

                TextColumn::make('trans.name')
                    ->searchable()
                    ->label('Name')
                    ->limit(50),

                TextColumn::make('order')
                    ->sortable()
                    ->label('Order'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                IconColumn::make('is_home')
                    ->boolean()
                    ->label('Home'),

                IconColumn::make('show_in_tab')
                    ->boolean()
                    ->label('Tab'),

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

                TernaryFilter::make('show_in_tab')
                    ->label('Show in Tab'),
            ])
            ->defaultSort('created_at', 'desc')
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
                    
                    BulkAction::make('show_on_home')
                        ->label('Show on Home')
                        ->icon('heroicon-o-home')
                        ->color('info')
                        ->action(fn ($records) => $records->each->update(['is_home' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    BulkAction::make('hide_from_home')
                        ->label('Hide from Home')
                        ->icon('heroicon-o-eye-slash')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['is_home' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
