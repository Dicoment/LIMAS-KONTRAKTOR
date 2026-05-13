<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Tabs::make('Tabs')->tabs([

                Tab::make('Konten')
                    ->icon('heroicon-o-document-text')
                    ->schema([

                        Section::make('Detail Halaman')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Halaman')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $context, $state, callable $set) =>
                                        $context === 'create'
                                            ? $set('slug', Str::slug($state))
                                            : null
                                    ),

                                TextInput::make('slug')
                                    ->label('Slug URL')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique('pages', 'slug', ignoreRecord: true)
                                    ->helperText('Diisi otomatis dari judul.'),

                                Toggle::make('is_active')
                                    ->label('Aktifkan Halaman')
                                    ->default(true)
                                    ->helperText('Halaman hanya tampil di website jika diaktifkan.'),

                                FileUpload::make('thumbnail_image')
                                    ->label('Thumbnail')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('pages/thumbnails')
                                    ->visibility('public')
                                    ->maxSize(3072)
                                    ->columnSpanFull(),

                                RichEditor::make('content')
                                    ->label('Isi Konten')
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'bulletList', 'orderedList', 'blockquote',
                                        'h2', 'h3', 'link',
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),

                Tab::make('SEO')
                    ->icon('heroicon-o-magnifying-glass')
                    ->schema([

                        Section::make('Meta Tag SEO')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(60)
                                    ->helperText(fn ($state) => strlen($state ?? '') . ' / 60 karakter')
                                    ->live(),

                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->helperText(fn ($state) => strlen($state ?? '') . ' / 160 karakter')
                                    ->live()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ]),

            ])->columnSpanFull(),

        ]);
    }
}