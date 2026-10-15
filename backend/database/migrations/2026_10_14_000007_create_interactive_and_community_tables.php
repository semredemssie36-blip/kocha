<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Citizen Opinion Polls
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('question_am');
            $table->string('question_en')->nullable();
            $table->string('question_om')->nullable();
            $table->text('description_am')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_om')->nullable();
            $table->date('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Poll Options
        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_id');
            $table->string('option_text_am');
            $table->string('option_text_en')->nullable();
            $table->string('option_text_om')->nullable();
            $table->unsignedBigInteger('votes_count')->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
        });

        // Poll Voter Records (IP hash + Cookie to prevent multiple voting)
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_id');
            $table->unsignedBigInteger('option_id');
            $table->string('voter_ip_hash', 64);
            $table->string('session_id')->nullable();
            $table->timestamps();

            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('poll_options')->onDelete('cascade');
            $table->unique(['poll_id', 'voter_ip_hash']);
        });

        // Internal Board / Municipal Staff Bulletin
        Schema::create('internal_board_topics', function (Blueprint $table) {
            $table->id();
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('category')->default('Notices'); // Notices, Memos, Urban Planning, Technical
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });

        Schema::create('internal_board_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('author_id'); // Authorized staff only
            $table->string('author_name');
            $table->string('author_department')->nullable();
            $table->text('content_am');
            $table->text('content_en')->nullable();
            $table->text('content_om')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamps();

            $table->foreign('topic_id')->references('id')->on('internal_board_topics')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Contact Us & Citizen Feedback Inbox
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->string('sender_email')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('target_sub_city')->nullable();
            $table->enum('status', ['unread', 'read', 'resolved', 'archived'])->default('unread');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        // Dynamic Custom Pages (Page Builder: /page/:slug)
        Schema::create('dynamic_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title_am');
            $table->string('title_en')->nullable();
            $table->string('title_om')->nullable();
            $table->string('banner_image')->nullable();
            $table->longText('content_am');
            $table->longText('content_en')->nullable();
            $table->longText('content_om')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dynamic_pages');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('internal_board_posts');
        Schema::dropIfExists('internal_board_topics');
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('polls');
    }
};
