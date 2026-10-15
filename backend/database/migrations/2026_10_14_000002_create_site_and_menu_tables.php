<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Site Global Settings
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value_am')->nullable();
            $table->text('value_en')->nullable();
            $table->text('value_om')->nullable();
            $table->timestamps();
        });

        // Hero Carousel Slides
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->text('subtitle_am')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->text('subtitle_om')->nullable();
            $table->string('button_text_am')->nullable();
            $table->string('button_text_en')->nullable();
            $table->string('button_text_om')->nullable();
            $table->string('button_url')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Emergency Hotlines & Contacts
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name_am');
            $table->string('name_en');
            $table->string('name_om');
            $table->string('phone_number');
            $table->string('icon_name')->default('PhoneCall');
            $table->string('badge_color')->default('red'); // red, orange, blue, amber
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Mega Menu & Footer Menu Items
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('menu_type', ['mega_menu', 'footer_quick_links', 'footer_col3', 'footer_col4'])->default('mega_menu');
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('url')->default('#');
            $table->string('icon_name')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('emergency_contacts');
        Schema::dropIfExists('hero_slides');
        Schema::dropIfExists('site_settings');
    }
};
