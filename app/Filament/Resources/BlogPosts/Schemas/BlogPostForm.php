<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Models\Category;
use App\Models\Tag;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Tabs::make('Tabs')->tabs([

                // ── Tab 1: Konten ───────────────────────────────────
                Tab::make('Konten')
                    ->icon('heroicon-o-document-text')
                    ->schema([

                        Section::make('Detail Artikel')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul Artikel')
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
                                    ->unique('blog_posts', 'slug', ignoreRecord: true)
                                    ->helperText('Diisi otomatis dari judul. Bisa diubah manual.'),

                                RichEditor::make('content')
                                    ->label('Isi Artikel')
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'bulletList', 'orderedList', 'blockquote',
                                        'h2', 'h3', 'link', 'codeBlock',
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Section::make('Kategori & Tag')
                            ->schema([
                                Select::make('categories')
                                    ->label('Kategori')
                                    ->relationship(
                                        'categories',
                                        'name',
                                        fn (Builder $query) => $query->where('type', 'blog')
                                    )
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nama Kategori')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) =>
                                                $set('slug', Str::slug($state))
                                            ),
                                        TextInput::make('slug')
                                            ->label('Slug')
                                            ->required(),
                                        Hidden::make('type')
                                            ->default('blog'),
                                    ]),

                                Select::make('tags')
                                    ->label('Tag')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nama Tag')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) =>
                                                $set('slug', Str::slug($state))
                                            ),
                                        TextInput::make('slug')
                                            ->label('Slug')
                                            ->required(),
                                    ]),
                            ])
                            ->columns(2),

                        Section::make('Publikasi')
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Publikasikan Artikel')
                                    ->helperText('Aktifkan untuk menampilkan artikel di website.')
                                    ->live(),

                                DateTimePicker::make('published_at')
                                    ->label('Tanggal Publikasi')
                                    ->native(false)
                                    ->helperText('Kosongkan untuk publish sekarang.')
                                    ->visible(fn ($get) => $get('is_published')),
                            ])
                            ->columns(2),
                    ]),

                // ── Tab 2: Media ────────────────────────────────────
                Tab::make('Media')
                    ->icon('heroicon-o-photo')
                    ->schema([

                        Section::make('Thumbnail')
                            ->schema([
                                FileUpload::make('thumbnail')
                                    ->label('Gambar Thumbnail')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatioOptions(['16:9', '4:3'])
                                    ->directory('blog/thumbnails')
                                    ->visibility('public')
                                    ->maxSize(3072)
                                    ->helperText('Format: JPG, PNG, WebP. Maks 3MB. Rasio ideal 16:9.')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                // ── Tab 3: SEO ──────────────────────────────────────
                Tab::make('SEO')
                    ->icon('heroicon-o-magnifying-glass')
                    ->schema([

                        Section::make('Meta Tag SEO')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(60)
                                    ->placeholder('Maks 60 karakter')
                                    ->helperText(fn ($state) => strlen($state ?? '') . ' / 60 karakter')
                                    ->live(),

                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->placeholder('Maks 160 karakter')
                                    ->helperText(fn ($state) => strlen($state ?? '') . ' / 160 karakter')
                                    ->live()
                                    ->columnSpanFull(),

                                FileUpload::make('og_image')
                                    ->label('OG Image (Social Media)')
                                    ->image()
                                    ->directory('blog/og')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->helperText('Ideal: 1200x630px, maks 2MB.')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Section::make('Preview Google')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('google_preview')
                                    ->label(false)
                                    ->state(function ($get) {
                                        $url         = config('app.url') . '/blog/' . ($get('slug') ?: 'slug-artikel');
                                        $title       = $get('meta_title') ?: $get('title') ?: '(Judul belum diisi)';
                                        $description = $get('meta_description') ?: '(Deskripsi belum diisi)';

                                        return new HtmlString("
                                            <div style=\"font-family:Arial,sans-serif;max-width:600px;padding:16px;border:1px solid #e0e0e0;border-radius:8px;background:#fff;\">
                                                <div style=\"font-size:12px;color:#006621;\">{$url}</div>
                                                <div style=\"font-size:18px;color:#1a0dab;font-weight:500;\">{$title}</div>
                                                <div style=\"font-size:13px;color:#545454;\">{$description}</div>
                                            </div>
                                        ");
                                    })
                                    ->html()
                                    ->columnSpanFull(),
                            ]),
                    ]),

            ])->columnSpanFull(),

        ]);
    }
}