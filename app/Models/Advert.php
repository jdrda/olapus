<?php
/**
 * Advert module model
 * 
 * Model for module Advert
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

class Advert extends Model
{
    /** Allowed relation filters for the administration. */
    protected $adminFilters = [
        'advertlocation' => ['relation' => 'advertlocations', 'label' => 'admin_module_advertlocation.name'],
    ];

    use SoftDeletes, AdminModelTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'advert';
    
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
    protected $fillable = ['name', 'caption', 'text', 'link_url', 
        'link_title', 'position'];
    
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
        'caption' => [
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
     * Advert location link
     * 
     * @return object
     */
    public function advertlocations(){
        
        return $this->belongsToMany(AdvertLocation::class, 'advert_advertlocation', 'advert_id', 'advertlocation_id');
    }
    
    /**
     * Process relationships
     * 
     * @param type $query
     * @return object
     */
    public function scopeRelationships($query){
        
        return $query->with('advertlocations');
    }
}
