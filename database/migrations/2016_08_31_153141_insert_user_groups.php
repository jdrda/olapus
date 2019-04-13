<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertUserGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert admin user
        DB::table('usergroup')->insert(
            array(
                array(
                    'id' => 1,
                    'name' => 'Superadmin',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                    'deleted_at' => NULL
                ),
                array(
                    'id' => 2,
                    'name' => 'Admin',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                    'deleted_at' => NULL
                ),
                array(
                    'id' => 3,
                    'name' => 'User',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                    'deleted_at' => NULL
                ),
                array(
                    'id' => 4,
                    'name' => 'Customer',
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
        DB::table('usergroup')->truncate();
    }
}
