<?php
/**
 * Article category module model
 * 
 * Model for module Article category
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

class ArticleCategory extends Model
{
    /** Allowed relation filters for the administration. */
    protected $adminFilters = [
        'article' => ['relation' => 'articles', 'label' => 'admin_module_article.name'],
    ];

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
    protected $fillable = ['name', 'color', 'meta_title', 'meta_description', 
        'meta_keywords', 'text', 'url'];
    
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
     * 
     * @var array
     */
    protected $defaultOrderBy = [
      'name' => 'asc'  
    ];
    
    /**
     * Articles link
     * 
     * @return object
     */
    public function articles(){
        
        return $this->belongsToMany(Article::class, 'article_articlecategory', 'articlecategory_id', 'article_id');
    }
    
    /**
     * Process relationships
     * 
     * @param query $query
     * @return query
     */
    public function scopeRelationships($query){
        
        return $query->with('articles');
    }
}
