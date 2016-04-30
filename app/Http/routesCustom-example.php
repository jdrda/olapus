<?php
/**
 * Example user frontend file
 * 
 * User route definition
 * 
 * @category Router
 * @subpackage General
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

/**
 * Frontend root
 */
Route::get('/', ['as' => 'root', function () {
        return view('welcome');
}]);
