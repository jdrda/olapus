<?php
/**
 * Comment module model
 * 
 * Model for module Comment
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

class Comment extends Model
{
     use SoftDeletes, AdminModelTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comment';
    
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
    protected $fillable = ['name', 'headline', 'text', 'email', 
        'url', 'approved', 'commentstatus_id', 'page_id', 'article_id'];
    
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
        'headline' => [
            'operator' => 'LIKE',
            'prefix' => '%',
            'sufix' => '%'
        ],
        'email' => [
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
     * Comment statuses link
     * 
     * @return object
     */
    public function commentstatuses(){
        
         return $this->belongsTo('App\CommentStatus', 'commentstatus_id');
    }
    
    /**
     * Articles link
     * 
     * @return object
     */
    public function articles(){
        
        return $this->belongsTo('App\Article', 'article_id');
    }
    
    /**
     * Pages link
     * 
     * @return object
     */
    public function pages(){
        
         return $this->belongsTo('App\Page', 'page_id');
    }
    
    /**
     * Process relationships
     * 
     * @param query $query
     * @return query
     */
    public function scopeRelationships($query){
        
        return $query->with('articles', 'pages', 'commentstatuses');
    }
}
