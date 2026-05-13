<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads_logs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('whatsapp_number');
            $table->string('source_page')->nullable(); // e.g. 'homepage', 'project-detail', 'blog'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads_logs');
    }
};
