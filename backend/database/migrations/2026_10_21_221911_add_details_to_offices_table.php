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
        Schema::table('offices', function (Blueprint $table) {
            $table->text('vision_am')->nullable()->after('mandate_summary_om');
            $table->text('vision_en')->nullable()->after('vision_am');
            $table->text('vision_om')->nullable()->after('vision_en');

            $table->text('mission_am')->nullable()->after('vision_om');
            $table->text('mission_en')->nullable()->after('mission_am');
            $table->text('mission_om')->nullable()->after('mission_en');

            $table->json('values_am')->nullable()->after('mission_om');
            $table->json('values_en')->nullable()->after('values_am');
            $table->json('values_om')->nullable()->after('values_en');

            $table->string('facebook_url')->nullable()->after('email');
            $table->string('telegram_url')->nullable()->after('facebook_url');
            $table->string('youtube_url')->nullable()->after('telegram_url');

            $table->json('additional_phones')->nullable()->after('phone');
            $table->json('facility_network')->nullable()->after('room_number');
            $table->json('room_breakdown')->nullable()->after('facility_network');

            $table->string('head_title_am')->nullable()->after('head_name_om');
            $table->string('head_title_en')->nullable()->after('head_title_am');
            $table->string('head_education_am')->nullable()->after('head_title_en');
            $table->string('head_education_en')->nullable()->after('head_education_am');
            $table->string('head_email')->nullable()->after('head_education_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropColumn([
                'vision_am', 'vision_en', 'vision_om',
                'mission_am', 'mission_en', 'mission_om',
                'values_am', 'values_en', 'values_om',
                'facebook_url', 'telegram_url', 'youtube_url',
                'additional_phones', 'facility_network', 'room_breakdown',
                'head_title_am', 'head_title_en', 'head_education_am', 'head_education_en', 'head_email'
            ]);
        });
    }
};
