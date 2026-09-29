<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fresh installations already get the new schema from create_users_table.
        // This migration only upgrades an existing Olapus users table.
        if (! Schema::hasColumn('users', 'fname')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['zip']);
            $table->dropIndex(['country']);
            $table->dropIndex(['da_zip']);
            $table->dropIndex(['da_country']);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'fname',
                'mname',
                'sname',
                'phone',
                'fax',
                'mobile',
                'im',
                'street',
                'hno',
                'city',
                'zip',
                'state',
                'country',
                'da_street',
                'da_hno',
                'da_city',
                'da_zip',
                'da_state',
                'da_country',
            ]);
        });

        Schema::table('users', function (Blueprint $table): void {
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
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'company_name') || Schema::hasColumn('users', 'fname')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'company_name',
                'contact_name',
                'contact_email',
                'contact_phone',
                'company_id',
                'vat_id',
                'website',
                'customer_number',
                'external_id',
                'locale',
                'timezone',
                'billing_name',
                'billing_contact_name',
                'billing_street',
                'billing_house_number',
                'billing_city',
                'billing_zip',
                'billing_state',
                'billing_country',
                'billing_email',
                'billing_phone',
                'shipping_name',
                'shipping_contact_name',
                'shipping_street',
                'shipping_house_number',
                'shipping_city',
                'shipping_zip',
                'shipping_state',
                'shipping_country',
                'shipping_email',
                'shipping_phone',
            ]);
        });

        Schema::table('users', function (Blueprint $table): void {
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
        });
    }
};
