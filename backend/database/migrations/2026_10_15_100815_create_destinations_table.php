<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('category')->default('nature'); // 'nature', 'industry', 'culture'
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('location_am')->nullable();
            $table->string('location_en')->nullable();
            $table->string('location_om')->nullable();
            $table->text('summary_am')->nullable();
            $table->text('summary_en')->nullable();
            $table->text('summary_om')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
