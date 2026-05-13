<?php

namespace App\Filament\Resources\LeadsLogs;

use App\Filament\Resources\LeadsLogs\Pages\ListLeadsLogs;
use App\Filament\Resources\LeadsLogs\Schemas\LeadsLogForm;
use App\Filament\Resources\LeadsLogs\Tables\LeadsLogsTable;
use App\Models\LeadsLog;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeadsLogResource extends Resource
{
    protected static ?string $model = LeadsLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static ?string $navigationLabel = 'Leads Masuk';

    protected static ?string $modelLabel = 'Lead';

    protected static ?string $pluralModelLabel = 'Leads Masuk';

    protected static string|UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    // Read-only — tidak bisa create/edit
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return LeadsLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadsLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeadsLogs::route('/'),
        ];
    }
}