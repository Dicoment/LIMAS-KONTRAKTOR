<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Project extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'client',
        'limas_role',
        'cover_image',
        'gallery',
        'status',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'status'  => ProjectStatus::class,
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    // ─── Relasi ──────────────────────────────────────────────────────────────
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_project');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'project_team')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', ProjectStatus::Completed);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereIn('status', [ProjectStatus::Ongoing, ProjectStatus::Completed]);
    }

    // ─── Accessors ───────────────────────────────────────────────────────────
    public function getSeoTitleAttribute(?string $value): string
    {
        return $value ?: $this->title;
    }
}