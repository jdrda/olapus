<?php
/**
 * Create article categories migration
 * 
 * Creates data table for module Article category
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
			$table->string('name')->index();
                        $table->string('color')->index()->default('default');
                        $table->string('meta_title')->nullable();
                        $table->string('meta_description')->nullable();
                        $table->string('meta_keywords')->nullable();
			$table->text('text')->nullable();
                        $table->string('url')->index();
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
