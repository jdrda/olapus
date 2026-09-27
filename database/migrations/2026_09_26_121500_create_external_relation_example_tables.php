<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_records', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('source_id');
            $table->unsignedBigInteger('external_id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['source_id', 'external_id'], 'external_records_source_external_unique');
        });

        Schema::create('external_rows', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('source_id');
            $table->unsignedBigInteger('record_external_id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['source_id', 'record_external_id']);
            $table->foreign(['source_id', 'record_external_id'], 'external_rows_record_fk')
                ->references(['source_id', 'external_id'])
                ->on('external_records')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_rows');
        Schema::dropIfExists('external_records');
    }
};
