<?php
/**
 * Advert location module model
 * 
 * Model for module Advert location
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

class AdvertLocation extends Model
{
    /** Allowed relation filters for the administration. */
    protected $adminFilters = [
        'advert' => ['relation' => 'adverts', 'label' => 'admin_module_advert.name'],
    ];

    use SoftDeletes, AdminModelTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'advertlocation';
    
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
    protected $fillable = ['name', 'description', 'color'];
    
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
     * Image link
     * 
     * @return object
     */
    public function adverts(){
        
        return $this->belongsToMany(Advert::class, 'advert_advertlocation', 'advertlocation_id', 'advert_id');
    }
    
    /**
     * Process relationships
     * 
     * @param type $query
     * @return object
     */
    public function scopeRelationships($query){
        
        return $query->with('adverts');
    }
}
