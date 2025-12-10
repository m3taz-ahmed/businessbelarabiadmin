<?php

namespace App\Filament\Resources\Podcasts;

use App\Filament\Resources\Podcasts\Pages\CreatePodcast;
use App\Filament\Resources\Podcasts\Pages\EditPodcast;
use App\Filament\Resources\Podcasts\Pages\ListPodcasts;
use App\Filament\Resources\Podcasts\Schemas\PodcastForm;
use App\Filament\Resources\Podcasts\Tables\PodcastsTable;
use App\Models\Podcast;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PodcastResource extends Resource
{
    protected static ?string $model = Podcast::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMicrophone;

    protected static string|UnitEnum|null $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    // Fix: Use a proper record title attribute that exists in the table
    protected static ?string $recordTitleAttribute = 'id';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['trans']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->trans->first()?->name ?? 'Untitled';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['trans.name', 'uuid'];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['trans']);
    }

    public static function form(Schema $schema): Schema
    {
        return PodcastForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PodcastsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPodcasts::route('/'),
            'create' => CreatePodcast::route('/create'),
            'edit' => EditPodcast::route('/{record}/edit'),
        ];
    }
}