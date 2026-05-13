<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\ProjectStatus;
use App\Models\Category;
use App\Models\Project;
use App\Models\Team;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // ── KOLOM KIRI (2/3) ──────────────────────────────────────────
            Grid::make(3)->schema([

                // Kiri: konten utama
                Grid::make(1)->schema([

                    Section::make('Informasi Proyek')
                        ->schema([
                            TextInput::make('title')
                                ->label('Judul Proyek')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true),

                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(Project::class, 'slug', ignoreRecord: true)
                                ->helperText('Otomatis terisi dari judul. Bisa diedit manual.'),
                        ])
                        ->columns(2),

                    Section::make('Deskripsi')
                        ->schema([
                            RichEditor::make('description')
                                ->label('')
                                ->toolbarButtons([
                                    'bold', 'italic', 'underline',
                                    'bulletList', 'orderedList',
                                    'link', 'blockquote',
                                ])
                                ->columnSpanFull(),
                        ]),

                    Section::make('Detail Proyek')
                        ->schema([
                            TextInput::make('location')
                                ->label('Lokasi Proyek')
                                ->placeholder('Contoh: Banten')
                                ->maxLength(255),

                            TextInput::make('client')
                                ->label('Klien')
                                ->placeholder('Contoh: PT. Taro')
                                ->maxLength(255),

                            TextInput::make('limas_role')
                                ->label('Peran Limas')
                                ->placeholder('Contoh: Kontraktor Utama')
                                ->maxLength(255),

                            Select::make('status')
                                ->label('Status Proyek')
                                ->options(ProjectStatus::options())
                                ->default(ProjectStatus::Draft->value)
                                ->native(false)
                                ->required(),
                        ])
                        ->columns(2),

                    Section::make('Kategori')
                        ->schema([
                            Select::make('categories')
                                ->label('Kategori Proyek')
                                ->relationship(
                                    name: 'categories',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn ($query) => $query->where('type', 'project')
                                )
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label('Nama Kategori')
                                        ->required(),
                                    Hidden::make('type')
                                        ->default('project'),
                                ])
                                ->helperText('Bisa pilih lebih dari satu. Klik "+ Buat baru" untuk tambah kategori.'),
                        ]),

                    Section::make('Tim Proyek')
                        ->schema([
                            Repeater::make('projectTeamMembers')
                                ->label('')
                                ->schema([
                                    Select::make('team_id')
                                        ->label('Anggota Tim')
                                        ->options(
                                            fn () => Team::where('is_active', true)
                                                ->get()
                                                ->mapWithKeys(fn ($t) => [
                                                    $t->id => $t->name . ' — ' . $t->position,
                                                ])
                                        )
                                        ->searchable()
                                        ->required()
                                        ->columnSpan(2),

                                    TextInput::make('role')
                                        ->label('Jabatan di Proyek Ini')
                                        ->placeholder('Contoh: Arsitek Utama, Struktur, MEP')
                                        ->columnSpan(2),
                                ])
                                ->columns(4)
                                ->addActionLabel('+ Tambah Anggota Tim')
                                ->defaultItems(0)
                                ->helperText('Kosongkan jika belum ada tim yang ditugaskan.'),
                        ]),

                    Section::make('Gallery Proyek')
                        ->schema([
                            FileUpload::make('gallery')
                                ->label('')
                                ->image()
                                ->multiple()
                                ->reorderable()
                                ->appendFiles()
                                ->directory('projects/gallery')
                                ->visibility('public')
                                ->imageEditor()
                                ->maxFiles(20)
                                ->helperText('Upload hingga 20 foto. Drag untuk mengubah urutan.'),
                        ]),

                ])->columnSpan(2),

                // Kanan: sidebar
                Grid::make(1)->schema([

                    Section::make('Cover Utama')
                        ->schema([
                            FileUpload::make('cover_image')
                                ->label('')
                                ->image()
                                ->directory('projects/covers')
                                ->visibility('public')
                                ->imageEditor()
                                ->helperText('Gambar utama yang tampil di listing portofolio.'),
                        ]),

                    Section::make('SEO')
                        ->schema([
                            TextInput::make('seo_title')
                                ->label('SEO Title')
                                ->maxLength(60)
                                ->placeholder('Kosongkan untuk pakai judul proyek')
                                ->helperText(fn (Get $get): string => 'Karakter: ' . strlen($get('seo_title') ?? '') . ' / 60')
                                ->live(),

                            Textarea::make('seo_description')
                                ->label('SEO Description')
                                ->maxLength(160)
                                ->rows(3)
                                ->placeholder('Deskripsi singkat untuk Google (maks 160 karakter)')
                                ->helperText(fn (Get $get): string => 'Karakter: ' . strlen($get('seo_description') ?? '') . ' / 160')
                                ->live(),
                        ]),

                ])->columnSpan(1),

            ]),

        ]);
    }
}