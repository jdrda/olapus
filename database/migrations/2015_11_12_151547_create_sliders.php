<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSliders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('slider', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->index();
                        $table->string('description')->nullable();
                        $table->integer('cycle_interval')->index()->unsigned()->default(5000);
			$table->timestamps();
                        $table->softDeletes();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('slider');
    }
}
