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
use Illuminate\Database\Eloquent\Builder;

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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFulltextAllColumns(Builder $query){
  
        return Helpers::virtualFulltextSearchColumns($query, request('search'), $this->fulltextFields);
    }
    
    /**
     * Order by
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByColumns(Builder $query){
        
        return Helpers::orderByColumns($query, $this->defaultOrderBy);
    }
   
    /**
     * Exclude columns
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExcludeFromIndex(Builder $query)
    {
        return $query->select( array_diff(Schema::getColumnListing($this->table), $this->excludedFromIndex) );
    }
    
    /**
     * Exclude from find
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExcludeFromFind(Builder $query)
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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRelationships(Builder $query){
        
        return $query;
    }
    
    /**
     * External table filter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExternalTablesFilter(Builder $query){
        
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
