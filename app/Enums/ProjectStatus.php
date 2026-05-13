<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Draft     = 'draft';
    case Ongoing   = 'ongoing';
    case Completed = 'completed';

    public function label(): string
    {
        return match($this) {
            self::Draft     => 'Draft',
            self::Ongoing   => 'Sedang Berjalan',
            self::Completed => 'Selesai',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft     => 'gray',
            self::Ongoing   => 'warning',
            self::Completed => 'success',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn ($case) => [$case->value => $case->label()]
        )->toArray();
    }
}