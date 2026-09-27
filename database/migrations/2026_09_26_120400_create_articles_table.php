<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('text')->nullable();
            $table->string('url')->index();
            $table->string('author_name')->nullable();
            $table->unsignedInteger('user_id')->default(1)->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('article_articlecategory', function (Blueprint $table): void {
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('articlecategory_id');
            $table->primary(['article_id', 'articlecategory_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_articlecategory');
        Schema::dropIfExists('article');
    }
};
