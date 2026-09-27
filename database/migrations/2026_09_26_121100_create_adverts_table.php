<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advert', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('caption')->nullable();
            $table->text('text')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_title')->nullable();
            $table->integer('position')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('advert_advertlocation', function (Blueprint $table): void {
            $table->unsignedInteger('advert_id');
            $table->unsignedInteger('advertlocation_id');
            $table->primary(['advert_id', 'advertlocation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advert_advertlocation');
        Schema::dropIfExists('advert');
    }
};
