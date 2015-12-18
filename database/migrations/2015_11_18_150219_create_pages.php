<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page', function (Blueprint $table) {
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
        
        Schema::create('page_pagecategory', function(Blueprint $table)
		{
			$table->integer('page_id')->unsigned();
                        $table->integer('pagecategory_id')->unsigned();
                        $table->index(['page_id', 'pagecategory_id']);
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('page');
        Schema::drop('page_pagecategory');
    }
}
