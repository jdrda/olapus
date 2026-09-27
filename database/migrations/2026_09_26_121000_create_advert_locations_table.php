<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertlocation', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->nullable()->index();
            $table->string('description')->nullable();
            $table->string('color');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertlocation');
    }
};
