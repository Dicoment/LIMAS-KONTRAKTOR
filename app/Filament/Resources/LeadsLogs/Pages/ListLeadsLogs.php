<?php

namespace App\Filament\Resources\LeadsLogs\Pages;

use App\Filament\Resources\LeadsLogs\LeadsLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeadsLogs extends ListRecords
{
    protected static string $resource = LeadsLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
