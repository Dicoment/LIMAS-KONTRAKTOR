<?php

namespace App\Models;

use App\Enums\TestimonialPlatform;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'platform',
        'social_link',
        'rating',
        'content',
        'is_active',
    ];

    protected $casts = [
        'platform'  => TestimonialPlatform::class,
        'rating'    => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderByDesc('rating');
    }

    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}   