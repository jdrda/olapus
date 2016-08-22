<?php
/**
 * Insert homepage migration
 * 
 * Creates homepage in module Page
 * 
 * @category Migration
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

use Illuminate\Database\Migrations\Migration;

class InsertHomepage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert admin user
        DB::table('page')->insert(
                        array(
                                array(
                                    'id' => 1,
                                    'name' => 'Homepage',
                                    'url' => '/',
                                    'meta_title' => 'Homepage',
                                    'meta_keywords' => 'Homepage',
                                    'meta_description' => 'Homepage',
                                    'created_at' => Carbon\Carbon::now(),
                                    'updated_at' => Carbon\Carbon::now(),
                                    'published_at' => Carbon\Carbon::now(),
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
        DB::table('page')->delete();
    }
}
