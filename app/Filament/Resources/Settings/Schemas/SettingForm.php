<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Setting')
                ->schema([
                    TextInput::make('key')
                        ->label('Key')
                        ->required()
                        ->maxLength(255)
                        ->unique('settings', 'key', ignoreRecord: true)
                        ->helperText('Contoh: whatsapp_number, site_name, site_description')
                        ->disabled(fn ($context) => $context === 'edit'),

                    Select::make('type')
                        ->label('Tipe Value')
                        ->options([
                            'text'  => 'Text',
                            'image' => 'Image',
                        ])
                        ->default('text')
                        ->required()
                        ->native(false)
                        ->live(),

                    Textarea::make('value')
                        ->label('Value')
                        ->rows(3)
                        ->helperText('Isi nilai dari setting ini.')
                        ->columnSpanFull()
                        ->visible(fn ($get) => $get('type') === 'text'),

                    FileUpload::make('value')
                        ->label('Value (Gambar)')
                        ->image()
                        ->directory('settings')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->columnSpanFull()
                        ->visible(fn ($get) => $get('type') === 'image'),
                ])
                ->columns(2),

        ]);
    }
}