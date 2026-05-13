<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'type', // 'blog' atau 'project'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────
    public function scopeForBlog(Builder $query): Builder
    {
        return $query->where('type', 'blog');
    }

    public function scopeForProject(Builder $query): Builder
    {
        return $query->where('type', 'project');
    }

    // ─── Relasi ──────────────────────────────────────────────────────────────
    public function blogPosts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_category');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'category_project');
    }
}