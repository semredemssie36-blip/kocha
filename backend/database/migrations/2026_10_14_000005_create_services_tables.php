<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Citizen Services (Text Service vs. Step Service)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->enum('type', ['text', 'step'])->default('step'); // Text guide vs Step-by-Step
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('slug')->unique();
            $table->text('summary_am')->nullable();
            $table->text('summary_en')->nullable();
            $table->text('summary_om')->nullable();
            $table->longText('content_am')->nullable(); // For text services
            $table->longText('content_en')->nullable();
            $table->longText('content_om')->nullable();
            $table->string('estimated_time_am')->nullable(); // e.g., '3 የስራ ቀናት'
            $table->string('estimated_time_en')->nullable(); // e.g., '3 Business Days'
            $table->string('estimated_time_om')->nullable(); // e.g., 'Guyyoota Hojii 3'
            $table->decimal('fee_amount', 10, 2)->default(0.00);
            $table->string('icon_name')->default('FileText');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null');
        });

        // Step-by-Step Civic Walkthrough Steps
        Schema::create('service_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->integer('step_number'); // 1, 2, 3...
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->text('instruction_am');
            $table->text('instruction_en')->nullable();
            $table->text('instruction_om')->nullable();
            $table->text('required_documents_am')->nullable();
            $table->text('required_documents_en')->nullable();
            $table->text('required_documents_om')->nullable();
            $table->string('responsible_desk_am')->nullable();
            $table->string('responsible_desk_en')->nullable();
            $table->string('responsible_desk_om')->nullable();
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->unique(['service_id', 'step_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_steps');
        Schema::dropIfExists('services');
    }
};
