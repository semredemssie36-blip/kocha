<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Resources & Directives
        Schema::table('resources', function (Blueprint $table) {
            if (!Schema::hasColumn('resources', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('office_id');
                $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('resources', 'status')) {
                $table->string('status', 30)->default('published')->after('downloads_count');
            }
        });

        // 2. Gallery Items
        Schema::table('gallery_items', function (Blueprint $table) {
            if (!Schema::hasColumn('gallery_items', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('album_id');
                $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('gallery_items', 'status')) {
                $table->string('status', 30)->default('published')->after('order');
            }
        });

        // 3. Tenders
        Schema::table('tenders', function (Blueprint $table) {
            if (!Schema::hasColumn('tenders', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('id');
                $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            }
        });
        // Broaden tenders status enum to allow draft & pending review
        try {
            DB::statement("ALTER TABLE tenders MODIFY COLUMN status ENUM('draft', 'pending', 'open', 'closed', 'cancelled') NOT NULL DEFAULT 'open'");
        } catch (\Exception $e) {
            // In case driver is sqlite or non-MySQL
        }

        // 4. Vacancies
        Schema::table('vacancies', function (Blueprint $table) {
            if (!Schema::hasColumn('vacancies', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('office_id');
                $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            }
        });
        try {
            DB::statement("ALTER TABLE vacancies MODIFY COLUMN status ENUM('draft', 'pending', 'open', 'closed', 'cancelled') NOT NULL DEFAULT 'open'");
        } catch (\Exception $e) {
            // fallback
        }

        // 5. Projects
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('office_id');
                $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('projects', 'publication_status')) {
                $table->string('publication_status', 30)->default('published')->after('status');
            }
        });

        // 6. Destinations
        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('id');
                $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('destinations', 'status')) {
                $table->string('status', 30)->default('published')->after('is_active');
            }
        });

        // 7. Services
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'author_id')) {
                $table->unsignedBigInteger('author_id')->nullable()->after('office_id');
                $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('services', 'status')) {
                $table->string('status', 30)->default('published')->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['author_id', 'status']);
        });
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['author_id', 'status']);
        });
        Schema::table('tenders', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['author_id']);
        });
        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['author_id']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['author_id', 'publication_status']);
        });
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['author_id', 'status']);
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['author_id', 'status']);
        });
    }
};
