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
            $table->decimal('latitude', 10, 7)->default(11.0845000)->after('is_maintenance');
            $table->decimal('longitude', 10, 7)->default(39.7390000)->after('latitude');
            $table->string('map_address_am')->nullable()->default('ዋናው አስተዳደር ኮሪደር፣ ቀበሌ 02፣ ኮምቦልቻ፣ አማራ ክልል፣ ኢትዮጵያ')->after('longitude');
            $table->string('map_address_en')->nullable()->default('Main Administrative Corridor, Kebele 02, Kombolcha, Amhara Region, Ethiopia')->after('map_address_am');
            $table->string('map_address_om')->nullable()->default('Kooridorii Bulchiinsa Magaalaa, Ganda 02, Kombolcha, Naannoo Amaaraa, Itoophiyaa')->after('map_address_en');
            $table->unsignedTinyInteger('map_zoom')->default(15)->after('map_address_om');
        });

        // Set default values for existing rows
        DB::table('site_settings')->update([
            'latitude' => 11.0845000,
            'longitude' => 39.7390000,
            'map_address_am' => 'ዋናው አስተዳደር ኮሪደር፣ ቀበሌ 02፣ ኮምቦልቻ፣ አማራ ክልል፣ ኢትዮጵያ',
            'map_address_en' => 'Main Administrative Corridor, Kebele 02, Kombolcha, Amhara Region, Ethiopia',
            'map_address_om' => 'Kooridorii Bulchiinsa Magaalaa, Ganda 02, Kombolcha, Naannoo Amaaraa, Itoophiyaa',
            'map_zoom' => 15,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'map_address_am',
                'map_address_en',
                'map_address_om',
                'map_zoom',
            ]);
        });
    }
};
