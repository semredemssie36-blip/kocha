<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // News Articles & Press Releases
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('slug')->unique();
            $table->string('category')->default('General'); // Development, Governance, Social, Investment
            $table->text('summary_am')->nullable();
            $table->text('summary_en')->nullable();
            $table->text('summary_om')->nullable();
            $table->longText('content_am');
            $table->longText('content_en')->nullable();
            $table->longText('content_om')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'pending', 'published'])->default('published');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'published_at']);
        });

        // City Events Calendar
        Schema::create('city_events', function (Blueprint $table) {
            $table->id();
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('slug')->unique();
            $table->string('venue_am');
            $table->string('venue_en')->nullable();
            $table->string('venue_om')->nullable();
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('description_am')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_om')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Job Vacancies (Civil service & industrial park jobs)
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id')->nullable();
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('position_code')->nullable();
            $table->string('employment_type')->default('Full-Time'); // Full-Time, Contract, TVET Apprenticeship
            $table->string('salary_grade')->nullable();
            $table->longText('requirements_am');
            $table->longText('requirements_en')->nullable();
            $table->longText('requirements_om')->nullable();
            $table->date('deadline');
            $table->enum('status', ['open', 'closed', 'cancelled'])->default('open');
            $table->string('application_guide_am')->nullable();
            $table->string('application_guide_en')->nullable();
            $table->string('application_guide_om')->nullable();
            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices')->onDelete('set null');
        });

        // Public Tenders (Procurement notices with live countdown)
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique(); // e.g. KMB/NCB/08/2026
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('category')->default('Works'); // Goods, Works, Consultancy, Services
            $table->text('description_am')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_om')->nullable();
            $table->decimal('bid_bond_amount', 15, 2)->nullable();
            $table->string('document_pdf_path')->nullable();
            $table->dateTime('closing_datetime');
            $table->dateTime('opening_datetime')->nullable();
            $table->enum('status', ['open', 'closed', 'cancelled'])->default('open');
            $table->timestamps();

            $table->index(['status', 'closing_datetime']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenders');
        Schema::dropIfExists('vacancies');
        Schema::dropIfExists('city_events');
        Schema::dropIfExists('news_articles');
    }
};
