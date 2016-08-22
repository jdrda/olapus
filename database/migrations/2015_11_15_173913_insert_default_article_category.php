<?php
/**
 * Insert default article category migration
 * 
 * Creates default category in module Article category
 * 
 * @category Migration
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

use Illuminate\Database\Migrations\Migration;

class InsertDefaultArticleCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        DB::table('articlecategory')->insert(
                        array(
                                array(
                                    'id' => 1,
                                    'name' => 'General',
                                    'color' => 'general',
                                    'meta_title' => 'General category',
                                    'meta_description' => 'General category',
                                    'meta_keywords' => 'General category',
                                    'url' => 'default',
                                    'created_at' => Carbon\Carbon::now(),
                                    'updated_at' => Carbon\Carbon::now(),
                                    'deleted_at' => NULL
                                    ),                             
                        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         DB::table('articlecategory')->where('id', 1)->delete();
    }
}
