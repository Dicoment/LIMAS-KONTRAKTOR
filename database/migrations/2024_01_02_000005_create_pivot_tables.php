<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Blog Post <-> Category
        Schema::create('blog_post_category', function (Blueprint $table) {
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->primary(['blog_post_id', 'category_id']);
        });

        // Blog Post <-> Tag
        Schema::create('blog_post_tag', function (Blueprint $table) {
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['blog_post_id', 'tag_id']);
        });

        // Project <-> Category
        Schema::create('category_project', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->primary(['project_id', 'category_id']);
        });

        // Project <-> Team (dengan kolom role untuk jabatan di proyek ini)
        Schema::create('project_team', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('role')->nullable(); // Jabatan spesifik di proyek ini
            $table->primary(['project_id', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_team');
        Schema::dropIfExists('category_project');
        Schema::dropIfExists('blog_post_tag');
        Schema::dropIfExists('blog_post_category');
    }
};
