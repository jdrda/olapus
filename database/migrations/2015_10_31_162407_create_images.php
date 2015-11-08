<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('image', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 255);
                        $table->string('description', 255);
                        $table->string('alt', 255);
                        $table->string('url', 255)->unique();
                        $table->integer('imagecategory_id')->unsigned()->index()->default(1);
                        $table->binary('image');
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
         Schema::drop('image');
    }
}
