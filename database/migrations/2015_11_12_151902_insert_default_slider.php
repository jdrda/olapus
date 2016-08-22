<?php
/**
 * Insert default slider migration
 * 
 * Creates default slider in module Slider
 * 
 * @category Migration
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

use Illuminate\Database\Migrations\Migration;

class InsertDefaultSlider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert admin user
        DB::table('slider')->insert(
                        array(
                                array(
                                    'id' => 1,
                                    'name' => 'Default',
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
        DB::table('slider')->where('id', 1)->delete();
    }
}
