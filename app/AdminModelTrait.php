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
use Illuminate\Database\Query;

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
     * @param \Illuminate\Database\Query $query
     * @return \Illuminate\Database\Query
     */
    public function scopeFulltextAllColumns($query){
  
        return Helpers::virtualFulltextSearchColumns($query, request('search'), $this->fulltextFields);
    }
    
    /**
     * Order by
     * 
     * @param \Illuminate\Database\Query $query
     * @return \Illuminate\Database\Query
     */
    public function scopeOrderByColumns($query){
        
        return Helpers::orderByColumns($query, $this->defaultOrderBy);
    }
   
    /**
     * Exclude columns
     * 
     * @param \Illuminate\Database\Query $query
     * @return \Illuminate\Database\Query
     */
    public function scopeExcludeFromIndex($query) 
    {
        return $query->select( array_diff(Schema::getColumnListing($this->table), $this->excludedFromIndex) );
    }
    
    /**
     * Exclude from find
     * 
     * @param \Illuminate\Database\Query $query
     * @return \Illuminate\Database\Query
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
     * @param \Illuminate\Database\Query $query
     * @return \Illuminate\Database\Query
     */
    public function scopeRelationships($query){
        
        return $query;
    }
    
    /**
     * External table filter
     * 
     * @param \Illuminate\Database\Query $query
     * @return \Illuminate\Database\Query
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
