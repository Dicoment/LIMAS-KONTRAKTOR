<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'whatsapp_number',
        'source_page',
    ];

    public static function capture(string $name, string $whatsapp, string $sourcePage): self
    {
        return static::create([
            'name'            => $name,
            'whatsapp_number' => $whatsapp,
            'source_page'     => $sourcePage,
        ]);
    }
}