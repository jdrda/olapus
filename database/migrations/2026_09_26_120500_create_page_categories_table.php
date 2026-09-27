<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagecategory', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('description')->nullable();
            $table->string('class')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagecategory');
    }
};
