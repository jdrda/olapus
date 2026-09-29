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

            $table->string('company_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 32)->nullable();
            $table->string('company_id', 64)->nullable()->index();
            $table->string('vat_id', 64)->nullable()->index();
            $table->string('website', 2048)->nullable();
            $table->string('customer_number', 64)->nullable()->index();
            $table->string('external_id', 128)->nullable()->index();
            $table->string('locale', 16)->nullable();
            $table->string('timezone', 64)->nullable();

            $table->string('billing_name')->nullable();
            $table->string('billing_contact_name')->nullable();
            $table->string('billing_street')->nullable();
            $table->string('billing_house_number', 32)->nullable();
            $table->string('billing_city', 128)->nullable();
            $table->string('billing_zip', 32)->nullable();
            $table->string('billing_state', 128)->nullable();
            $table->string('billing_country', 2)->nullable()->index();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone', 32)->nullable();

            $table->string('shipping_name')->nullable();
            $table->string('shipping_contact_name')->nullable();
            $table->string('shipping_street')->nullable();
            $table->string('shipping_house_number', 32)->nullable();
            $table->string('shipping_city', 128)->nullable();
            $table->string('shipping_zip', 32)->nullable();
            $table->string('shipping_state', 128)->nullable();
            $table->string('shipping_country', 2)->nullable()->index();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone', 32)->nullable();

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
