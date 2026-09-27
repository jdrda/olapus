<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();

            $table->string('activation_code')->nullable()->index();
            $table->boolean('active')->default(false)->index();

            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('sname')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('mobile')->nullable();
            $table->string('im')->nullable();

            $table->string('street')->nullable();
            $table->string('hno')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable()->index();
            $table->string('state')->nullable();
            $table->string('country')->nullable()->index();

            $table->string('da_street')->nullable();
            $table->string('da_hno')->nullable();
            $table->string('da_city')->nullable();
            $table->string('da_zip')->nullable()->index();
            $table->string('da_state')->nullable();
            $table->string('da_country')->nullable()->index();

            $table->text('notice')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
