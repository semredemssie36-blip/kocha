<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('site_name_am')->nullable()->after('id');
            $table->string('site_name_en')->nullable()->after('site_name_am');
            $table->string('site_name_om')->nullable()->after('site_name_en');
            $table->string('contact_phone')->nullable()->after('site_name_om');
            $table->string('contact_email')->nullable()->after('contact_phone');
            $table->text('emergency_notice_am')->nullable()->after('contact_email');
            $table->text('emergency_notice_en')->nullable()->after('emergency_notice_am');
            $table->text('emergency_notice_om')->nullable()->after('emergency_notice_en');
            $table->boolean('is_maintenance')->default(false)->after('emergency_notice_om');
        });

        // Insert initial default configuration row
        DB::table('site_settings')->insert([
            'site_name_am' => 'የኮምቦልቻ ከተማ አስተዳደር',
            'site_name_en' => 'Kombolcha City Administration',
            'site_name_om' => 'Bulchiinsa Magaalaa Kombolchaa',
            'contact_phone' => '+251-33-551-0024',
            'contact_email' => 'info@kombolcha.gov.et',
            'emergency_notice_am' => '',
            'emergency_notice_en' => '',
            'emergency_notice_om' => '',
            'is_maintenance' => false,
            'key' => 'general_settings',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_name_am',
                'site_name_en',
                'site_name_om',
                'contact_phone',
                'contact_email',
                'emergency_notice_am',
                'emergency_notice_en',
                'emergency_notice_om',
                'is_maintenance',
            ]);
        });
    }
};
