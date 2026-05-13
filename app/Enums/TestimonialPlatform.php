<?php

namespace App\Enums;

enum TestimonialPlatform: string
{
    case Manual      = 'manual';
    case SocialMedia = 'social_media';

    public function label(): string
    {
        return match($this) {
            self::Manual      => 'Manual (Input Langsung)',
            self::SocialMedia => 'Social Media',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn ($case) => [$case->value => $case->label()]
        )->toArray();
    }
}