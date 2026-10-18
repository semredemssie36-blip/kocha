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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable()->index();
            $table->string('user_role')->nullable()->index();
            $table->string('action', 50)->index(); // LOGIN, LOGIN_FAILED, LOGOUT, CREATE, UPDATE, DELETE, APPROVE, UNPUBLISH
            $table->string('module', 50)->index(); // auth, news, tenders, vacancies, services, projects, resources, gallery, destinations, staff, settings
            $table->string('record_id', 50)->nullable()->index();
            $table->text('description');
            $table->json('changes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();

            // Foreign key to users table with null on delete
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
