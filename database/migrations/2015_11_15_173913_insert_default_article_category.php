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
                                    'name' => 'Default',
                                    'color' => 'default',
                                    'meta_title' => 'Default category',
                                    'meta_description' => 'Default category',
                                    'meta_keywords' => 'Default category',
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
         DB::table('articlecategory')->delete();
    }
}
