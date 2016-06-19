<?php
/**
 * Create product categories migration
 * 
 * Creates data table for module Product categories
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

class CreateProductCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productcategory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('small_description')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->index();
            $table->string('icon')->nullable();
            $table->string('image_url')->nullable();
            $table->text('other_images_url')->nullable();
            $table->integer('total_products')->unsigned()->default(0)->nullable();
            $table->integer('parent_id')->index()->unsigned()->nullable();
            $table->bigInteger('affil_id')->index()->unsigned()->nullable();
            $table->string('affil_url')->nullable();
            $table->integer('image_id')->unsigned()->index()->nullable();
            $table->boolean('featured')->index()->default(false);
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
        Schema::drop('productcategory');
    }
}
