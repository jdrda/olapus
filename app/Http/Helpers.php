<?php
/**
 * Helpers
 * 
 * Basic parent controller for admin functions
 * 
 * @category Helper
 * @subpackage General
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;

class Helpers
{

    /**
     * Search columns like fulltext do
     *
     * @param Query $query
     * @param string $word
     * @param array $fields
     * @return Query
     */
    public static function virtualFulltextSearchColumns($query, $word, $fields)
    {

        if (isset($word) && strlen($word) > 0) {

            $first = TRUE;

            foreach ($fields as $fieldName => $fieldAttributes) {

                /**
                 * Empty attributes
                 */
                if (is_numeric($fieldName) == TRUE) {
                    $fieldName = $fieldAttributes;
                    $fieldAttributes = ['operator' => '=', 'prefix' => '', 'sufix' => ''];
                }
                if (isset($fieldAttributes['operator']) == FALSE) {
                    $fieldAttributes['operator'] = '=';
                }
                if (isset($fieldAttributes['prefix']) == FALSE) {
                    $fieldAttributes['prefix'] = '';
                }
                if (isset($fieldAttributes['sufix']) == FALSE) {
                    $fieldAttributes['sufix'] = '';
                }

                /**
                 * Query builder
                 */
                if ($first == TRUE) {

                    $query->where($fieldName, $fieldAttributes['operator'], $fieldAttributes['prefix'] . $word
                        . $fieldAttributes['sufix']);

                    $first = FALSE;
                } else {
                    $query->orWhere($fieldName, $fieldAttributes['operator'], $fieldAttributes['prefix'] . $word
                        . $fieldAttributes['sufix']);
                }
            }
        }

        return $query;
    }

    /**
     * Order by columns based on parameters
     *
     * @param Query $query
     * @param string $orderBy
     * @return Query
     */
    public static function orderByColumns($query, $orderBy)
    {

        if (Request::has('orderbycolumn') == TRUE && Request::has('orderbytype') == TRUE) {

            $query->orderBy(Request::input('orderbycolumn'), Request::input('orderbytype'));
        } else {
            foreach ($orderBy as $orderByColumn => $orderByType) {
                $query->orderBy($orderByColumn, $orderByType);
            }
        }

        return $query;
    }

    /**
     * Reset or save index parameters
     *
     * @param string $module
     * @param array $possibleParameters
     * @return boolean
     */
    public static function resetSaveIndexParameters($module, $possibleParameters = ['search', 'orderbycolumn', 'orderbytype', 'relation'])
    {

        /**
         * Get common parameters
         */
        $allParameters = Request::all();

        /**
         * Parse existing parameters
         */
        foreach ($allParameters as $parameter => $value) {

            $cacheKey = implode('.', [$module, Auth::user()->id, $parameter]);

            /**
             * Reset
             */
            if (empty($value) == TRUE) {

                Cache::forget($cacheKey);
                unset($allParameters[$parameter]);
            } /**
             * Save
             */ else {

                Cache::forever($cacheKey, $value);
            }
        }

        /**
         * Get new from cache
         */
        foreach ($possibleParameters as $parameter => $value) {

            $cacheKey = implode('.', [$module, Auth::user()->id, $value]);

            $allParameters[$value] = Cache::get($cacheKey);
        }

        /**
         * Sort parameters
         */
        ksort($allParameters);
        ksort($possibleParameters);

        /**
         * Redirect ?
         */
        $newRoute = trim(route($module . '.index', $allParameters), "?");
        $oldRoute = Request::fullUrl();

        /**
         * Routes are the same, everything is OK
         */
        if ($newRoute == $oldRoute) {
            return FALSE;
        } /**
         * Routes are different, return new route for redirect
         */
        else {
            return ($newRoute);
        }
    }

    /**
     * Format exact bytesize
     *
     * @param integer $bytes
     * @return string
     */
    public static function formatByteSize($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' b';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' b';
        } else {
            $bytes = '0 b';
        }

        return $bytes;
    }

    /**
     * Get method name from route action without namespace
     *
     * @return string
     */
    public static function getRouteMethod()
    {

        $method = explode('@', \Illuminate\Routing\Route::getCurrentRoute()->getActionName());
        return end($method);
    }

    /**
     * Exclude columns from table
     *
     * @param string $table
     * @param array $columns
     */
    function excludeColumns($table, $columns = array())
    {
        return array_diff(Schema::getColumnListing($table), $columns);
    }

    /**
     * Get structured name for storage
     *
     * @param string $directory
     * @param integer $id
     * @return string
     */
    public static function getStorageFilename($directory, $id)
    {

        $numberedDirectory = implode('/', str_split($id));

        $filename = $directory . "/" . $numberedDirectory . "/" . $id . ".data";

        return $filename;
    }

    /**
     * Get all Font awesome icon classes
     *
     * @return array
     */
    public static function getFontAwesomeIcons()
    {

        $rawFile = file(base_path() . "/bower_components/font-awesome/scss/_icons.scss");
        $icons = array();

        foreach ($rawFile as $row) {

            if (preg_match('/content: \$fa-var-(.*)\;/', $row, $matches) == TRUE) {

                $icons[] = "fa-" . $matches[1];
            }

        }

        return $icons;
    }
}
