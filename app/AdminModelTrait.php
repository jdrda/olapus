<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request;

/**
 * Description of AdminModelTrait
 *
 * @author Uzivatel
 */
trait AdminModelTrait {
    
    /**
     * Workaround to get table name
     * 
     * @return type
     */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
    
    /**
     * Scope for fulltext search
     * 
     * @param query $query
     * @param string $word
     */
    public function scopeFulltextAllColumns($query){
  
        return virtualFulltextSearchColumns($query, request('search'), $this->fulltextFields);
    }
    
    /**
     * Order by
     * 
     * @param type $query
     */
    public function scopeOrderByColumns($query){
        
        return orderByColumns($query, $this->defaultOrderBy);
    }
   
    /**
     * Exclude columns
     * 
     * @param type $query
     * @param type $value
     * @return type
     */
    public function scopeExcludeFromIndex($query) 
    {
        return $query->select( array_diff(Schema::getColumnListing($this->table), $this->excludedFromIndex) );
    }
    
    /**
     * Exclude from find
     * 
     * @param type $query
     * @param type $value
     * @return type
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
     */
    public function scopeRelationships($query){
        
        return $query;
    }
    
    /**
     * External table filter
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
