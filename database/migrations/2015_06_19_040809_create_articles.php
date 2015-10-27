<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 255);
                        $table->string('meta_description', 255);
                        $table->string('meta_keywords', 255);
			$table->text('text');
                        $table->string('url', 255)->unique();
                        $table->binary('image');
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles');
    }
}
