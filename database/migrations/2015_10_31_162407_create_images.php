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
			$table->string('name');
                        $table->string('description')->nullable();
                        $table->string('alt')->nullable();
                        $table->string('url')->index();
                        $table->integer('imagecategory_id')->unsigned()->index()->default(1);
                        $table->string('image')->nullable();
                        $table->string('image_mime_type')->index()->nullable();
                        $table->string('image_extension')->index()->nullable();
                        $table->string('image_original_name')->nullable();
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
