<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlides extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slide', function (Blueprint $table) 
                {
			$table->increments('id');
			$table->string('name', 255)->unique();
                        $table->string('description', 255)->nullable();
                        $table->string('caption', 255)->nullable();
                        $table->string('text', 1024)->nullable();
                        $table->integer('position')->index()->default(1);
                        $table->integer('slider_id')->index()->unsigned()->default(1);
                        $table->integer('image_id')->index()->unsigned()->nullable();
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
        Schema::drop('slide');
    }
}
