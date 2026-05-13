<?php

namespace App\Filament\Resources\LeadsLogs\Pages;

use App\Filament\Resources\LeadsLogs\LeadsLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLeadsLog extends EditRecord
{
    protected static string $resource = LeadsLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
