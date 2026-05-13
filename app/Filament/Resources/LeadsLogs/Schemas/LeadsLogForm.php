<?php

namespace App\Filament\Resources\LeadsLogs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadsLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Lead')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->disabled(),

                    TextInput::make('whatsapp_number')
                        ->label('Nomor WhatsApp')
                        ->disabled(),

                    TextInput::make('source_page')
                        ->label('Halaman Asal')
                        ->disabled()
                        ->columnSpanFull(),
                ])
                ->columns(2),

        ]);
    }
}