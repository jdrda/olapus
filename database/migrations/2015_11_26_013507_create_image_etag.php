<?php
/**
 * Create image etag migration
 * 
 * Creates column etag in data table for module Image
 * 
 * @category Migration
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

use Illuminate\Database\Migrations\Migration;
use Closure;

class CreateImageEtag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('image', function (Closure $table) {
            $table->string('image_etag')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('image', function (Closure $table) {
            $table->dropColumn('image_etag');
        });
    }
}
