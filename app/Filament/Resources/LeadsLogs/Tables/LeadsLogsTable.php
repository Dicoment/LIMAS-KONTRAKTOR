<?php

namespace App\Filament\Resources\LeadsLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeadsLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('whatsapp_number')
                    ->label('WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor disalin!'),

                TextColumn::make('source_page')
                    ->label('Halaman Asal')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}