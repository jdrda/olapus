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
                        $table->string('description', 255)->nullable();
                        $table->string('alt', 255)->nullable();
                        $table->string('url', 255)->unique();
                        $table->integer('imagecategory_id')->unsigned()->index()->default(1);
                        $table->string('image', 255)->nullable();
                        $table->string('image_mime_type', 255)->index()->nullable();
                        $table->string('image_extension', 255)->index()->nullable();
                        $table->string('image_original_name', 255)->nullable();
                        $table->integer('image_size')->unsigned()->default(0);
                        $table->integer('image_width')->unsigned()->default(0);;
                        $table->integer('image_height')->unsigned()->default(0);
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
