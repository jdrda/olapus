<?php
/**
 * Insert admin migration
 * 
 * Creates default user - admin in module User
 * 
 * @category Migration
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

use Illuminate\Database\Migrations\Migration;

class InsertAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert admin user
        DB::table('users')->insert(
                        array(
                                array(
                                    'id' => 1,
                                    'name' => 'system',
                                    'email' => 'system@system.com',
                                    'password' => Hash::make('iueywa8087awe0hfg'),
                                    'created_at' => Carbon\Carbon::now(),
                                    'updated_at' => Carbon\Carbon::now(),
                                    'deleted_at' => NULL
                                    ),  
                                array(
                                    'id' => 2,
                                    'name' => 'admin',
                                    'email' => 'admin@admin.com',
                                    'password' => Hash::make('admin'),
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
        DB::table('users')->where('id', 1)->delete();
        DB::table('users')->where('id', 2)->delete();
    }
}
