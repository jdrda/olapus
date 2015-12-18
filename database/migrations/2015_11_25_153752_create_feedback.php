<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('feedback', function (Blueprint $table) 
                {
			$table->increments('id');
			$table->string('name')->index();
                        $table->string('description')->nullable();
                        $table->integer('position')->index()->default(1);
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
        Schema::drop('feedback');
    }
}
