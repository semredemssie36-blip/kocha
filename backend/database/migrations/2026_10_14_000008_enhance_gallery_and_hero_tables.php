<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hero_slides')) {
            Schema::table('hero_slides', function (Blueprint $table) {
                if (!Schema::hasColumn('hero_slides', 'tag_am')) {
                    $table->string('tag_am')->nullable()->after('image_path');
                }
                if (!Schema::hasColumn('hero_slides', 'tag_en')) {
                    $table->string('tag_en')->nullable()->after('tag_am');
                }
                if (!Schema::hasColumn('hero_slides', 'tag_om')) {
                    $table->string('tag_om')->nullable()->after('tag_en');
                }
            });
        }

        if (Schema::hasTable('gallery_items')) {
            Schema::table('gallery_items', function (Blueprint $table) {
                if (!Schema::hasColumn('gallery_items', 'category')) {
                    $table->string('category')->default('all')->after('media_type');
                }
                if (!Schema::hasColumn('gallery_items', 'thumbnail_url')) {
                    $table->string('thumbnail_url')->nullable()->after('media_path_or_url');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('hero_slides')) {
            Schema::table('hero_slides', function (Blueprint $table) {
                $table->dropColumn(['tag_am', 'tag_en', 'tag_om']);
            });
        }

        if (Schema::hasTable('gallery_items')) {
            Schema::table('gallery_items', function (Blueprint $table) {
                $table->dropColumn(['category', 'thumbnail_url']);
            });
        }
    }
};
