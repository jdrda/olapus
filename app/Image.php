<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes, AdminModelTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'image';
    
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
    protected $fillable = ['name', 'description', 'alt', 'url', 'image', 
        'imagecategory_id', 'image_mime_type', 'image_extension', 
        'image_original_name', 'image_size', 'image_width', 'image_height'];
    
    /**
     * Columns to exclude from index
     * 
     * @var type 
     */
    protected $excludedFromIndex = ['image'];
    
    /**
     * Hidden from custom find
     * 
     * @var type 
     */
    protected  $excludedFromFind = ['image'];
    
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
        'description' => [
            'operator' => 'LIKE',
            'prefix' => '%',
            'sufix' => '%'
        ],
        'alt' => [
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
      'id' => 'desc'  
    ];
    
    /**
     * Image category link
     * 
     * @return type
     */
    public function imagecategories(){
        
        return $this->belongsTo('App\ImageCategory', 'imagecategory_id');
    }
    
    /**
     * Slide link
     * 
     * @return type
     */
    public function slides(){
        
        return $this->hasMany('App\Slide', 'image_id');
    }
    
    /**
     * Article category
     * 
     * @return type
     */
    public function articlecategories(){
        
        return $this->hasMany('App\ArticleCategory', 'image_id');
    }
    
    /**
     * Article category
     * 
     * @return type
     */
    public function pages(){
        
        return $this->hasMany('App\Page', 'image_id');
    }
    
    /**
     * Process relationships
     * 
     * @param type $query
     */
    public function scopeRelationships($query){
        
        return $query->with('imagecategories');
    }
    
    /**
     * Hide fields
     * 
     * @param type $array
     */
    public function hide($array = []){
        
        $this->hidden = ['image'];
    }
}
