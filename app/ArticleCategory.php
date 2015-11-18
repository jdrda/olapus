<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Model
{
    use SoftDeletes, AdminModelTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'articlecategory';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'color', 'meta_title', 'meta_description', 
        'meta_keywords', 'text', 'url', 'image_id',];
    
    /**
     * Columns to exclude from index
     * 
     * @var type 
     */
    protected $excludedFromIndex = ['image'];

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
        'meta_title' => [
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
     */
    protected $defaultOrderBy = [
      'name' => 'asc'  
    ];
    
    /**
     * Image link
     * 
     * @return type
     */
    public function images(){
        
        return $this->belongsTo('App\Image', 'image_id')->select('id', 'name', 'description', 
                'alt', 'url', 'imagecategory_id', 'image_mime_type', 'image_extension', 
                'image_original_name', 'image_size', 'image_width', 'image_height',
                'created_at', 'updated_at', 'deleted_at');
    }
    
    /**
     * Articles link
     * 
     * @return type
     */
    public function articles(){
        
        return $this->belongsToMany('App\Article', 'article_articlecategory', 'articlecategory_id', 'article_id');
    }
    
    /**
     * Process relationships
     * 
     * @param type $query
     */
    public function scopeRelationships($query){
        
        return $query->with('images', 'articles');
    }
}
