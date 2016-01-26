<?php

use Illuminate\Database\Migrations\Migration;

class InsertTestComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert admin user
        DB::table('comment')->insert(
                        array(
                                array(
                                    'id' => 1,
                                    'name' => 'John Doe',
                                    'headline' => 'Test comment',
                                    'url' => 'http://www.website.com',
                                    'email' => 'johndoe@website.com',
                                    'text' => 'Hello, this is test comment',
                                    'commentstatus_id' => 1,
                                    'page_id' => 1,
                                    'article_id' => NULL,
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
        DB::table('comment')->where('id', 1)->delete();
    }
}
