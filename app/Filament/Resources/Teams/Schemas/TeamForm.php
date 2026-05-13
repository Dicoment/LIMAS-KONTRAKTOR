<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Anggota Tim')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('position')
                        ->label('Jabatan')
                        ->required()
                        ->placeholder('Contoh: Project Manager, Site Engineer')
                        ->maxLength(255),

                    Toggle::make('is_active')
                        ->label('Tampilkan di Website')
                        ->default(true)
                        ->helperText('Aktifkan untuk menampilkan anggota ini di halaman publik.'),

                    FileUpload::make('photo')
                        ->label('Foto')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatioOptions(['1:1'])
                        ->directory('teams/photos')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->helperText('Foto profil. Ideal: persegi (1:1), maks 2MB.')
                        ->columnSpanFull(),

                    Textarea::make('bio')
                        ->label('Bio / Deskripsi')
                        ->rows(4)
                        ->placeholder('Tulis bio singkat anggota tim...')
                        ->columnSpanFull(),
                ])
                ->columns(2),

        ]);
    }
}