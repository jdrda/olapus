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
        Schema::create('articlecategory', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 255)->unique();
                        $table->string('color', 255)->index()->default('default');
                        $table->string('meta_title', 255)->nullable();
                        $table->string('meta_description', 255)->nullable();
                        $table->string('meta_keywords', 255)->nullable();
			$table->text('text')->nullable();
                        $table->string('url', 255)->unique();
                        $table->integer('image_id')->unsigned()->index()->nullable();
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
        Schema::drop('articlecategory');
    }
}
