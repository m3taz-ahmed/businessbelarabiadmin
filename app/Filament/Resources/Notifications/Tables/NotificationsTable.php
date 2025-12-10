<?php

namespace App\Filament\Resources\Notifications\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;

class NotificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Title')
                    ->limit(50),

                TextColumn::make('body')
                    ->limit(50)
                    ->label('Body'),

                TextColumn::make('user.name')
                    ->searchable()
                    ->label('User'),

                IconColumn::make('is_sent')
                    ->boolean()
                    ->label('Sent'),

                IconColumn::make('is_seen')
                    ->boolean()
                    ->label('Seen'),

                IconColumn::make('is_push_notification')
                    ->boolean()
                    ->label('Push'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created At'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_sent')
                    ->label('Sent Status'),

                TernaryFilter::make('is_seen')
                    ->label('Seen Status'),

                TernaryFilter::make('is_push_notification')
                    ->label('Push Notification'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('mark_as_sent')
                        ->label('Mark as Sent')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_sent' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    BulkAction::make('mark_as_seen')
                        ->label('Mark as Seen')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->action(fn ($records) => $records->each->update(['is_seen' => true]))
                        ->deselectRecordsAfterCompletion(),
                    
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
