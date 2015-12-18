<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdverts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 255)->index();
                        $table->string('caption', 255)->nullable();
			$table->text('text')->nullable();
                        $table->string('link_url', 255)->nullable();
                        $table->string('link_title', 255)->nullable();
                        $table->integer('image_id')->unsigned()->index()->nullable();
                        $table->integer('position')->index();
			$table->timestamps();
                        $table->softDeletes();
		});
                
        Schema::create('advert_advertlocation', function(Blueprint $table)
		{
			$table->integer('advert_id')->unsigned();
                        $table->integer('advertlocation_id')->unsigned();
                        $table->primary(['advert_id', 'advertlocation_id']);
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('advert');
        Schema::drop('advert_advertlocation');
    }
}
