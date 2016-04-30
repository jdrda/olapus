<?php
/**
 * Create comments migration
 * 
 * Creates data table for module Comment
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

class CreateComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->index()->nullable();
                        $table->string('headline')->nullable();
			$table->text('text')->nullable();
                        $table->string('email')->index()->nullable();
                        $table->string('url')->nullable();
                        $table->integer('article_id')->unsigned()->index()->nullable();
                        $table->integer('page_id')->unsigned()->index()->nullable();
                        $table->integer('commentstatus_id')->unsigned()->index()->default(1);
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
        Schema::drop('comment');
    }
}
