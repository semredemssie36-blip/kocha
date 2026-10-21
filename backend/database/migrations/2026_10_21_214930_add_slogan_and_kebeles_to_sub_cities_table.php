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
        Schema::table('sub_cities', function (Blueprint $table) {
            $table->string('slogan_am')->nullable()->after('slug');
            $table->string('slogan_en')->nullable()->after('slogan_am');
            $table->string('slogan_om')->nullable()->after('slogan_en');
            $table->json('kebeles')->nullable()->after('description_om');
            $table->json('highlights')->nullable()->after('kebeles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_cities', function (Blueprint $table) {
            $table->dropColumn(['slogan_am', 'slogan_en', 'slogan_om', 'kebeles', 'highlights']);
        });
    }
};
