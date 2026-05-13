<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Enums\TestimonialPlatform;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Pelanggan')
                ->schema([
                    TextInput::make('customer_name')
                        ->label('Nama Pelanggan')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('customer_position')
                        ->label('Jabatan / Perusahaan')
                        ->placeholder('Contoh: CEO, PT Maju Jaya')
                        ->maxLength(255),

                    FileUpload::make('customer_photo')
                        ->label('Foto Pelanggan')
                        ->image()
                        ->imageEditor()
                        ->directory('testimonials/photos')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->helperText('Opsional. Foto profil pelanggan.')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Detail Testimoni')
                ->schema([
                    Select::make('platform')
                        ->label('Platform')
                        ->options(TestimonialPlatform::class)
                        ->default('manual')
                        ->required()
                        ->native(false),

                    TextInput::make('social_link')
                        ->label('Link Sosial Media')
                        ->placeholder('https://google.com/review/...')
                        ->url()
                        ->maxLength(500),

                    Select::make('rating')
                        ->label('Rating')
                        ->options([
                            1 => '⭐ 1 Bintang',
                            2 => '⭐⭐ 2 Bintang',
                            3 => '⭐⭐⭐ 3 Bintang',
                            4 => '⭐⭐⭐⭐ 4 Bintang',
                            5 => '⭐⭐⭐⭐⭐ 5 Bintang',
                        ])
                        ->default(5)
                        ->required()
                        ->native(false),

                    Toggle::make('is_active')
                        ->label('Tampilkan di Website')
                        ->helperText('Aktifkan untuk menampilkan testimoni ini di halaman publik.')
                        ->default(true),

                    Textarea::make('content')
                        ->label('Isi Testimoni')
                        ->rows(4)
                        ->placeholder('Tulis isi testimoni pelanggan di sini...')
                        ->columnSpanFull(),
                ])
                ->columns(2),

        ]);
    }
}