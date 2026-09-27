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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    /** Allowed relation filters for the administration. */
    protected $adminFilters = [
        'pagecategory' => ['relation' => 'pagecategories', 'label' => 'admin_module_pagecategory.name'],
        'user' => ['relation' => 'users', 'label' => 'admin_module_user.name'],
    ];

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
        'meta_keywords', 'text', 'url', 'user_id', 'published_at'];
    
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
     * Page categories link
     * 
     * @return object
     */
    public function pagecategories(){
        
        return $this->belongsToMany(PageCategory::class, 'page_pagecategory', 'page_id', 'pagecategory_id');
    }
    
    /**
     * Comments
     * 
     * @return object
     */
    public function comments(){
        
        return $this->hasMany(Comment::class, 'page_id');
    }
    
    /**
     * Users link
     * 
     * @return object
     */
    public function users(){
        
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Process relationships
     * 
     * @param query $query
     * @return query
     */
    public function scopeRelationships($query){
        
        return $query->with('pagecategories', 'users');
    }
}
