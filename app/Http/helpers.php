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
            } 
            else {
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
    } 
    else {
        foreach ($orderBy as $orderByColumn => $orderByType) {
            $query->orderBy($orderByColumn, $orderByType);
        }
    }

    return $query;
}

function resetSaveIndexParameters($module, $possibleParameters = ['search', 'orderbycolumn', 'orderbytype']){
    
    /**
     * Get common parameters
     */
    $allParameters = Request::all();
    
    /**
     * Parse existing parameters
     */
    foreach ($allParameters as $parameter => $value){
        
        $cacheKey = implode('.', [$module, Auth::user()->id, $parameter]);
        
        /**
         * Reset
         */
        if(empty($value) == TRUE){
            
            Cache::forget($cacheKey);
            unset($allParameters[$parameter]);
        }
        /**
         * Save
         */
        else{
        
            Cache::forever($cacheKey, $value);
        }
    }
    
    /**
     * Get new from cache
     */
    foreach($possibleParameters as $parameter => $value){
        
        $cacheKey = implode('.', [$module, Auth::user()->id, $value]);
        
        $allParameters[$value] = Cache::get($cacheKey);
    }
    
    /**
     * Sort parameters
     */
    asort($allParameters);
    
    /**
     * Redirect ?
     */
    $newRoute = trim(route($module.'.index', $allParameters), "?");
    $oldRoute = Request::fullUrl();
    
    /**
     * Routes are the same, everything is OK
     */
    if(array_intersect(Request::input(), $allParameters) == Request::input()){
        return FALSE;
    }
    /**
     * Routes are different, return new route for redirect
     */
    else{
       return($newRoute);
    }
}
