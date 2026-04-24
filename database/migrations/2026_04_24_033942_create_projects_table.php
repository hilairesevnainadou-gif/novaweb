<?php
// database/migrations/2024_01_01_000001_create_projects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_number')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('web');
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_manager_id')->constrained('users');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('estimated_hours', 10, 2)->default(0);
            $table->decimal('actual_hours', 10, 2)->default(0);
            $table->decimal('budget', 12, 2)->nullable();
            $table->string('status')->default('planning');
            $table->string('priority')->default('medium');
            $table->integer('progress_percentage')->default(0);
            $table->string('repository_url')->nullable();
            $table->string('production_url')->nullable();
            $table->string('staging_url')->nullable();
            $table->json('technologies')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index('project_number');
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->cascadeOnDelete();
            $table->string('task_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->string('status')->default('todo');
            $table->string('priority')->default('medium');
            $table->string('task_type')->default('task');
            $table->decimal('estimated_hours', 10, 2)->default(0);
            $table->decimal('actual_hours', 10, 2)->default(0);
            $table->datetime('start_date')->nullable();
            $table->datetime('due_date')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users');
            $table->text('completion_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->datetime('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->json('attachments')->nullable();
            $table->integer('order')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['project_id', 'status']);
            $table->index('assigned_to');
            $table->index('task_number');
        });

        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('meeting_date');
            $table->integer('duration_minutes');
            $table->string('meeting_link')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('organizer_id')->constrained('users');
            $table->json('attendees')->nullable();
            $table->text('minutes')->nullable();
            $table->text('decisions')->nullable();
            $table->json('action_items')->nullable();
            $table->string('status')->default('scheduled');
            $table->boolean('recorded')->default(false);
            $table->timestamps();

            $table->index(['project_id', 'meeting_date']);
            $table->index('status');
        });

        // Créer d'abord les tables sans contraintes externes problématiques
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->text('comment');
            $table->json('attachments')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('task_comments')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('project_id')->constrained();
            $table->date('date');
            $table->decimal('hours', 5, 2);
            $table->text('description')->nullable();
            $table->boolean('is_billable')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'date']);
            $table->index('project_id');
        });

        // Table des discussions (nommée project_discussions)
        Schema::create('project_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->string('title');
            $table->text('content');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });

        // Table des messages de discussion - CORRECTION : référence à project_discussions
        Schema::create('discussion_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discussion_id')->constrained('project_discussions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->timestamps();
        });

        Schema::create('task_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->integer('size');
            $table->string('mime_type');
            $table->timestamps();
        });

        Schema::create('project_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('activity_type');
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_activities');
        Schema::dropIfExists('task_attachments');
        Schema::dropIfExists('discussion_messages');
        Schema::dropIfExists('project_discussions');
        Schema::dropIfExists('time_entries');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('projects');
    }
};
