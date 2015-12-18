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
            $table->string('name')->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('text')->nullable();
            $table->string('url')->unique();
            $table->string('author_name')->nullable();
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
