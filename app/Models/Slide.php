<?php
/**
 * Slide module model
 * 
 * Model for module Slide
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

class Slide extends Model
{
    /** Allowed relation filters for the administration. */
    protected $adminFilters = [
        'slider' => ['relation' => 'sliders', 'label' => 'admin_module_slider.name'],
    ];

    use SoftDeletes, AdminModelTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'slide';
    
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
    protected $fillable = ['name', 'description', 'caption', 'text', 'position', 'slider_id'];
    
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
        'description' => [
            'operator' => 'LIKE',
            'prefix' => '%',
            'sufix' => '%'
        ],
        'position'
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
     * Slide link
     * 
     * @return object
     */
    public function sliders(){
        
        return $this->belongsTo(Slider::class, 'slider_id');
    }
    
    /**
     * Process relationships
     * 
     * @param query $query
     * @return query
     */
    public function scopeRelationships($query){
        
        return $query->with('sliders');
    }
}
