<?php
/**
 * Image module model
 * 
 * Model for module Image
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
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

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
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'alt', 'url'];
    
    /**
     * Columns to exclude from index
     * 
     * @var array 
     */
    protected $excludedFromIndex = [];
    
    /**
     * Hidden from custom find
     * 
     * @var arrat 
     */
    protected  $excludedFromFind = [];
    
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
     * 
     * @type array
     */
    protected $defaultOrderBy = [
      'id' => 'desc'  
    ];
    
    /**
     * Image category link
     * 
     * @return object
     */
    public function imagecategories(){
        
        return $this->belongsTo('App\ImageCategory', 'imagecategory_id');
    }
    
    /**
     * Slide link
     * 
     * @return object
     */
    public function slides(){
        
        return $this->hasMany('App\Slide', 'image_id');
    }
    
    /**
     * Article category
     * 
     * @return object
     */
    public function articlecategories(){
        
        return $this->hasMany('App\ArticleCategory', 'image_id');
    }
    
    /**
     * Article category
     * 
     * @return object
     */
    public function pages(){
        
        return $this->hasMany('App\Page', 'image_id');
    }
    
    /**
     * Process relationships
     * 
     * @param query $query
     * @return query
     */
    public function scopeRelationships($query){
        
        return $query->with('imagecategories');
    }
    
    /**
     * Hide fields
     * 
     * @param array $array
     */
    public function hide($array = []){
        
        $this->hidden = ['image'];
    }
}
