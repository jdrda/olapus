<?php
/**
 * Admin model trait
 * 
 * Basic admin model functions
 * 
 * @category Model
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request;

trait AdminModelTrait {
    
    /**
     * Workaround to get table name
     * 
     * @return string
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    
    /**
     * Scope for fulltext search
     * 
     * @param query $query
     * @return query
     */
    public function scopeFulltextAllColumns($query){
  
        return virtualFulltextSearchColumns($query, request('search'), $this->fulltextFields);
    }
    
    /**
     * Order by
     * 
     * @param query $query
     * @return query
     */
    public function scopeOrderByColumns($query){
        
        return orderByColumns($query, $this->defaultOrderBy);
    }
   
    /**
     * Exclude columns
     * 
     * @param query $query
     * @return query
     */
    public function scopeExcludeFromIndex($query) 
    {
        return $query->select( array_diff(Schema::getColumnListing($this->table), $this->excludedFromIndex) );
    }
    
    /**
     * Exclude from find
     * 
     * @param query $query
     * @return query
     */
    public function scopeExcludeFromFind($query) 
    {
        if(isset($this->excludedFromFind) == TRUE && is_array($this->excludedFromFind) == TRUE){
            return $query->select( array_diff(Schema::getColumnListing($this->table), $this->excludedFromFind) );
        }
        else{
            return $query;
        }
    }
    
    /**
     * Process relationships
     * 
     * @param type $query
     * @return query
     */
    public function scopeRelationships($query){
        
        return $query;
    }
    
    /**
     * External table filter
     * 
     * @param query $query
     * @return query
     */
    public function scopeExternalTablesFilter($query){
        
        if(Request::input('relation')){
            
            $allTables = [];
            
            $relations = explode(',', Request::input('relation'));
            
            foreach ($relations as $relation){
                
                $keyvalue = explode(':', $relation);
                
                $key = trim($keyvalue[0]);
                $value = trim($keyvalue[1]);
                
                $allTables[$key] = $value;
                
                if(is_numeric($value) == TRUE){
                    
                    $query->where(strtolower($key)."_id", '=', $value);
                }
            }
            
             request()->merge(['external_tables_filter' => $allTables]);
        }
        
        return $query;
    }
}
