<?php
/**
 * Create product migration
 * 
 * Creates data table for module Product and pivot table to module Product category
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

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('small_description')->nullable();
            $table->text('description')->nullable();
            $table->string('url')->index();
            $table->string('image_url')->nullable();
            $table->text('other_images_url')->nullable();
            $table->text('attributes')->nullable();
            $table->boolean('free_shipping')->index()->nullable()->default(false);
            $table->boolean('available')->index()->default(true);
            $table->string('package_type')->nullable();
            $table->float('purchase_price')->index()->nullable();
            $table->float('purchase_price_vat')->index()->nullable();
            $table->float('price')->index()->nullable();
            $table->float('price_vat')->index()->nullable();
            $table->float('sale_price')->index()->nullable();
            $table->float('sale_price_vat')->index()->nullable();
            $table->float('vat')->index()->nullable();
            $table->string('affil_url');
            $table->float('rating')->index()->default(0);
            $table->bigInteger('affil_id')->index()->unsigned()->nullable();
            $table->integer('image_id')->unsigned()->index()->nullable();
            $table->boolean('indexed')->default(0); 
            $table->integer('quantity')->unsigned()->index()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('product_productcategory', function(Blueprint $table)
		{
			$table->bigInteger('product_id')->unsigned();
                        $table->bigInteger('productcategory_id')->unsigned();
                        $table->primary(['product_id', 'productcategory_id']);
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product');
        Schema::drop('product_productcategory');
    }
}
