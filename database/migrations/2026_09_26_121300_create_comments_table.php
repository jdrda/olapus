<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->nullable()->index();
            $table->string('headline')->nullable();
            $table->text('text')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('url')->nullable();
            $table->unsignedInteger('article_id')->nullable()->index();
            $table->unsignedInteger('page_id')->nullable()->index();
            $table->unsignedInteger('commentstatus_id')->default(1)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment');
    }
};
