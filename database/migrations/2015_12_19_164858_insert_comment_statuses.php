<?php
/**
 * Insert comment statuses migration
 * 
 * Creates standard statuses in module Comment status
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

class InsertCommentStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert admin user
        DB::table('commentstatus')->insert(
                        array(
                                array(
                                    'id' => 1,
                                    'name' => 'Waiting for approvement',
                                    'color' => 'primary',
                                    'created_at' => Carbon\Carbon::now(),
                                    'updated_at' => Carbon\Carbon::now(),
                                    'deleted_at' => NULL
                                    ),   
                                array(
                                    'id' => 2,
                                    'name' => 'Approved',
                                    'color' => 'success',
                                    'created_at' => Carbon\Carbon::now(),
                                    'updated_at' => Carbon\Carbon::now(),
                                    'deleted_at' => NULL
                                    ), 
                                array(
                                    'id' => 3,
                                    'name' => 'Declined',
                                    'color' => 'danger',
                                    'created_at' => Carbon\Carbon::now(),
                                    'updated_at' => Carbon\Carbon::now(),
                                    'deleted_at' => NULL
                                    ), 
                                 array(
                                    'id' => 4,
                                    'name' => 'SPAM',
                                    'color' => 'warning',
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
       DB::table('commentstatus')->delete();
    }
}
