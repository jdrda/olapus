<?php

/**
 * Search columns like fulltext do
 * 
 * @param type $query
 * @param type $word
 * @param type $fields
 * @return type
 */
function virtualFulltextSearchColumns($query, $word, $fields) {

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
 * @param type $query
 * @param type $orderBy
 * @return type
 */
function orderByColumns($query, $orderBy) {

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
 * Generate parameters for blade
 * 
 * @param type $orderBy
 */
function orderByColumnsBlade($orderBy) {

    $result = array();
    $iterator = 0;

    /**
     * Non default value
     */
    if (Request::has('orderbycolumn') == TRUE && Request::has('orderbytype') == TRUE) {

        foreach ($orderBy as $orderByColumn => $orderByType) {

            /**
             * Change order by type
             */
            if (Request::input('orderbycolumn') == $orderByColumn) {

                if ($orderByType == 'asc') {

                    $result[$orderByColumn] = 'desc';
                } else {

                    $result[$orderByColumn] = 'asc';
                }
            }

            /**
             * Default order type
             */ else {
                $result[$orderByColumn] = 'asc';
            }
        }
    }
    /**
     * Default value
     */ else {
        foreach ($orderBy as $orderByColumn => $orderByType) {
            $result[$orderByColumn] = 'asc';
        }
    }
    
    print_r($result);
    die;
}
