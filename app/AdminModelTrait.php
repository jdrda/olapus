<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use Illuminate\Support\Facades\Schema;

/**
 * Description of AdminModelTrait
 *
 * @author Uzivatel
 */
trait AdminModelTrait {
    
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
    public function scopeExclude($query) 
    {
        return $query->select( array_diff(Schema::getColumnListing($this->table), $this->excludedFromIndex) );
    }
}
