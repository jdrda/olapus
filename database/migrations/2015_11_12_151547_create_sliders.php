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
			$table->string('name', 255)->unique();
                        $table->string('description', 255)->nullable();
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
