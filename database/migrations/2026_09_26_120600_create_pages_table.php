<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('text')->nullable();
            $table->string('url')->unique();
            $table->string('author_name')->nullable();
            $table->unsignedInteger('user_id')->default(1)->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_pagecategory', function (Blueprint $table): void {
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('pagecategory_id');
            $table->index(['page_id', 'pagecategory_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_pagecategory');
        Schema::dropIfExists('page');
    }
};
