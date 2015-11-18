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
        Schema::create('article', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 255)->unique();
                        $table->string('meta_title', 255)->nullable();
                        $table->string('meta_description', 255)->nullable();
                        $table->string('meta_keywords', 255)->nullable();
			$table->text('text')->nullable();
                        $table->string('url', 255)->unique();
                        $table->string('author_name', 255)->nullable();
                        $table->integer('image_id')->unsigned()->index()->nullable();
                        $table->integer('user_id')->unsigned()->index()->default(1);
                        $table->timestamp('published_at')->nullable();
			$table->timestamps();
                        $table->softDeletes();
		});
                
         Schema::create('article_articlecategory', function(Blueprint $table)
		{
			$table->integer('article_id')->unsigned();
                        $table->integer('articlecategory_id')->unsigned();
                        $table->primary(['article_id', 'articlecategory_id']);
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('article');
        Schema::drop('article_articlecategory');
    }
}
