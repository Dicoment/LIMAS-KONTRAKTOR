<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default settings
        DB::table('settings')->insert([
            ['key' => 'whatsapp_number',    'value' => '6281234567890', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_address',    'value' => 'Jl. Contoh No. 1, Jakarta', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'meta_keywords',      'value' => 'kontraktor, renovasi, bangun rumah', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_name',       'value' => 'Limas Kontraktor', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_email',      'value' => 'info@limaskontraktor.com', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
