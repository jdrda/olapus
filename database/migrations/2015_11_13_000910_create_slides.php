<?php
/**
 * Create slides migration
 * 
 * Creates data table for module Slide
 * 
 * @category Migration
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlides extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slide', function (Blueprint $table) 
                {
			$table->increments('id');
			$table->string('name')->index();
                        $table->string('description')->nullable();
                        $table->string('caption')->nullable();
                        $table->string('text')->nullable();
                        $table->integer('position')->index()->default(1);
                        $table->integer('slider_id')->index()->unsigned()->default(1);
                        $table->integer('image_id')->index()->unsigned()->nullable();
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
        Schema::drop('slide');
    }
}
