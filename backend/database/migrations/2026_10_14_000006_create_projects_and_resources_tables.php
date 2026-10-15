<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Infrastructure & Mega Projects Tracker
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('slug')->unique();
            $table->text('description_am')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_om')->nullable();
            $table->decimal('budget_etb', 18, 2)->nullable();
            $table->string('funding_source')->nullable(); // City Treasury, Regional Grant, World Bank
            $table->string('contractor_name')->nullable();
            $table->string('supervising_engineer')->nullable();
            $table->integer('progress_percent')->default(0); // 0 to 100%
            $table->date('start_date')->nullable();
            $table->date('target_completion_date')->nullable();
            $table->enum('status', ['planning', 'in_progress', 'completed', 'halted'])->default('in_progress');
            $table->string('image_before')->nullable();
            $table->string('image_after')->nullable();
            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null');
        });

        // Resources & Public Document Download Center
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('category')->default('Directives'); // Bylaws, Directives, Annual Reports, Forms, Master Plan
            $table->integer('year')->default(2026);
            $table->text('description_am')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_om')->nullable();
            $table->string('file_path');
            $table->string('file_extension')->default('pdf');
            $table->string('file_size_formatted')->nullable(); // e.g. "2.4 MB"
            $table->unsignedBigInteger('downloads_count')->default(0);
            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null');
            $table->index(['category', 'year']);
        });

        // Photo & Video Gallery Albums
        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->integer('year')->default(2026);
            $table->timestamps();
        });

        // Gallery Items (Photos and Embedded Video Links)
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('album_id')->nullable();
            $table->enum('media_type', ['image', 'video'])->default('image');
            $table->string('media_path_or_url'); // Storage path for photos, YouTube URL for videos
            $table->string('caption_am')->nullable();
            $table->string('caption_en')->nullable();
            $table->string('caption_om')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('album_id')->references('id')->on('gallery_albums')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('gallery_albums');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('projects');
    }
};
