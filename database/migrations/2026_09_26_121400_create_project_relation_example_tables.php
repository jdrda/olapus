<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->unsignedInteger('owner_id')->nullable()->index();
            $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_labels', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_label_project', function (Blueprint $table): void {
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_label_id')->constrained('project_labels')->cascadeOnDelete();
            $table->primary(['project_id', 'project_label_id']);
        });

        Schema::create('project_tasks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('completed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_tasks');
        Schema::dropIfExists('project_label_project');
        Schema::dropIfExists('project_labels');
        Schema::dropIfExists('projects');
    }
};
