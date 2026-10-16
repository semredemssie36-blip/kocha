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
        Schema::table('destinations', function (Blueprint $table) {
            $table->longText('description_am')->nullable()->after('summary_om');
            $table->longText('description_en')->nullable()->after('description_am');
            $table->longText('description_om')->nullable()->after('description_en');
            $table->string('visiting_hours')->nullable()->after('image_path');
            $table->string('contact_phone')->nullable()->after('visiting_hours');
            $table->string('contact_email')->nullable()->after('contact_phone');
            $table->string('admission_fee')->nullable()->after('contact_email');
            $table->text('features')->nullable()->after('admission_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn([
                'description_am',
                'description_en',
                'description_om',
                'visiting_hours',
                'contact_phone',
                'contact_email',
                'admission_fee',
                'features'
            ]);
        });
    }
};
