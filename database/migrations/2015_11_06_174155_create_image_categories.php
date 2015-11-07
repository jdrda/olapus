<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagecategory', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description');
            $table->string('color');
            $table->timestamps();
            $table->softDeletes();
        });
        
        /**
         * Pivot table to images
         */
        Schema::create('image_imagecategory', function (Blueprint $table) {
            $table->integer('image_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('image_category');
        Schema::drop('image_imagecategory');
    }
}
