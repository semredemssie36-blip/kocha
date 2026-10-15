<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4 Sub-Cities (ክፍለ ከተሞች: Abishager, Borkena, Hasenager, Biraro)
        Schema::create('sub_cities', function (Blueprint $table) {
            $table->id();
            $table->string('name_am');
            $table->string('name_en');
            $table->string('name_om');
            $table->string('slug')->unique();
            $table->string('chief_executive_am')->nullable();
            $table->string('chief_executive_en')->nullable();
            $table->string('chief_executive_om')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('office_address_am')->nullable();
            $table->string('office_address_en')->nullable();
            $table->string('office_address_om')->nullable();
            $table->text('description_am')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_om')->nullable();
            $table->string('photo_path')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 22 Municipal & Sector Offices
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_city_id')->nullable();
            $table->string('name_am');
            $table->string('name_en');
            $table->string('name_om');
            $table->string('slug')->unique();
            $table->string('head_name_am')->nullable();
            $table->string('head_name_en')->nullable();
            $table->string('head_name_om')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('building_location_am')->nullable();
            $table->string('building_location_en')->nullable();
            $table->string('building_location_om')->nullable();
            $table->string('room_number')->nullable();
            $table->string('working_hours_am')->default('ከሰኞ - አርብ (2:00 - 11:30)');
            $table->string('working_hours_en')->default('Monday - Friday (8:00 AM - 5:30 PM)');
            $table->string('working_hours_om')->default('Wiixata - Jimaata (Ganama 2:00 - 11:30)');
            $table->text('mandate_summary_am')->nullable();
            $table->text('mandate_summary_en')->nullable();
            $table->text('mandate_summary_om')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('sub_city_id')->references('id')->on('sub_cities')->onDelete('set null');
        });

        // Mayor Profile & History
        Schema::create('mayors', function (Blueprint $table) {
            $table->id();
            $table->string('name_am');
            $table->string('name_en');
            $table->string('name_om');
            $table->string('portrait_path')->nullable();
            $table->string('term_start')->nullable();
            $table->string('term_end')->nullable();
            $table->boolean('is_current')->default(true);
            $table->text('welcome_message_am')->nullable();
            $table->text('welcome_message_en')->nullable();
            $table->text('welcome_message_om')->nullable();
            $table->longText('biography_am')->nullable();
            $table->longText('biography_en')->nullable();
            $table->longText('biography_om')->nullable();
            $table->timestamps();
        });

        // Cabinet & City Council Members
        Schema::create('leadership_members', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['cabinet', 'council']); // Cabinet executive vs City council
            $table->string('name_am');
            $table->string('name_en');
            $table->string('name_om');
            $table->string('position_am');
            $table->string('position_en');
            $table->string('position_om');
            $table->string('photo_path')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leadership_members');
        Schema::dropIfExists('mayors');
        Schema::dropIfExists('offices');
        Schema::dropIfExists('sub_cities');
    }
};
