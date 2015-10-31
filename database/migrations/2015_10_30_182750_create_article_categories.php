<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_category', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 255)->unique();
                        $table->string('color', 255);
                        $table->string('meta_description', 255);
                        $table->string('meta_keywords', 255);
			$table->text('text');
                        $table->string('url', 255)->unique();
                        $table->binary('image');
                        $table->integer('article_id')->unsigned();
                        $table->foreign('article_id')->references('id')->on('article');
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
        Schema::drop('article_category');
    }
}
