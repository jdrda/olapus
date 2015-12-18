<?php

use Illuminate\Database\Schema\Blueprint;
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
         // Insert admin user
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
