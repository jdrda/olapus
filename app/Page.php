<?php
/**
 * Page module model
 * 
 * Model for module Page
 * 
 * @category Model
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
     use SoftDeletes, AdminModelTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'page';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'published_at' => 'datetime',
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'author_name', 'meta_title', 'meta_description', 
        'meta_keywords', 'text', 'url', 'image_id', 'user_id', 'published_at'];
    
    /**
     * Columns to exclude from index
     * 
     * @var array 
     */
    protected $excludedFromIndex = [];

    /**
     * Fields to search in fulltext mode
     * 
     * @var array
     */
    protected $fulltextFields = [
        'id',
        'name' => [
            'operator' => 'LIKE',
            'prefix' => '%',
            'sufix' => '%'
        ],
         'url' => [
            'operator' => 'LIKE',
            'prefix' => '%',
            'sufix' => '%'
        ],
    ];
    
    /**
     * Default order by
     * 
     * @var array
     */
    protected $defaultOrderBy = [
      'id' => 'desc'  
    ];
    
    /**
     * Image link
     * 
     * @return object
     */
    public function images(){
        
        return $this->belongsTo('App\Image', 'image_id')->select('id', 'name', 'description', 
                'alt', 'url', 'imagecategory_id', 'image_mime_type', 'image_extension', 
                'image_original_name', 'image_size', 'image_width', 'image_height',
                'created_at', 'updated_at', 'deleted_at');
    }
    
    /**
     * Page categories link
     * 
     * @return object
     */
    public function pagecategories(){
        
        return $this->belongsToMany('App\PageCategory', 'page_pagecategory', 'page_id', 'pagecategory_id');
    }
    
    /**
     * Comments
     * 
     * @return object
     */
    public function comments(){
        
        return $this->hasMany('App\Comment', 'page_id');
    }
    
    /**
     * Users link
     * 
     * @return object
     */
    public function users(){
        
        return $this->belongsTo('App\User', 'user_id');
    }
    
    /**
     * Process relationships
     * 
     * @param query $query
     * @return query
     */
    public function scopeRelationships($query){
        
        return $query->with('images', 'images.imagecategories', 'pagecategories', 'users');
    }
}
