<?php

namespace App\Filament\Resources\ContactUs;

use App\Filament\Resources\ContactUs\Pages\CreateContactUs;
use App\Filament\Resources\ContactUs\Pages\EditContactUs;
use App\Filament\Resources\ContactUs\Pages\ListContactUs;
use App\Filament\Resources\ContactUs\Schemas\ContactUsForm;
use App\Filament\Resources\ContactUs\Tables\ContactUsTable;
use App\Models\ContactUs;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ContactUsResource extends Resource
{
    protected static ?string $model = ContactUs::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|UnitEnum|null $navigationGroup = 'Communications';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ContactUsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactUsTable::configure($table);
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
            'index' => ListContactUs::route('/'),
            'create' => CreateContactUs::route('/create'),
            'edit' => EditContactUs::route('/{record}/edit'),
        ];
    }
}
