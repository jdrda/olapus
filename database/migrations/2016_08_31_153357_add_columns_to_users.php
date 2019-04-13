<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->integer('usergroup_id')->unsigned()->index()->default(2);
            $table->string('activation_code')->index()->nullable();
            $table->boolean('active')->index()->default(false);
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn(['usergroup_id', 'age', 'blacklisted', 'ip', 'activation_code', 'active',
                'fname', 'sname', 'bno', 'city', 'citypart', 'street', 'hno', 'zip']);
        });
    }
}
