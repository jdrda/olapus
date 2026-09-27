<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slide', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('description')->nullable();
            $table->string('caption')->nullable();
            $table->string('text')->nullable();
            $table->integer('position')->default(1)->index();
            $table->unsignedInteger('slider_id')->default(1)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slide');
    }
};
