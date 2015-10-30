<?php

use Illuminate\Database\Schema\Blueprint;
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
        DB::table('users')->delete();
    }
}
