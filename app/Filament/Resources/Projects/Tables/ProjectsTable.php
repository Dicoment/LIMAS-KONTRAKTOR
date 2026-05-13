<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Enums\ProjectStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->square(),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->client),

                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('categories.name')
                    ->label('Kategori')
                    ->badge()
                    ->separator(', '),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(ProjectStatus::class)
                    ->native(false),

                SelectFilter::make('categories')
                    ->label('Kategori')
                    ->relationship(
                        'categories',
                        'name',
                        fn (Builder $query) => $query->where('type', 'project')
                    )
                    ->searchable()
                    ->preload(),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('completed')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['status' => ProjectStatus::Completed]))
                        ->requiresConfirmation()
                        ->color('success'),

                    BulkAction::make('draft')
                        ->label('Jadikan Draft')
                        ->icon('heroicon-o-eye-slash')
                        ->action(fn ($records) => $records->each->update(['status' => ProjectStatus::Draft]))
                        ->requiresConfirmation()
                        ->color('warning'),

                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}