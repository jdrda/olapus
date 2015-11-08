<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settings extends Model
{
    use SoftDeletes, AdminModelTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';
    
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
    protected $fillable = ['name', 'value', 'description'];
    
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
        'value' => [
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
     */
    protected $defaultOrderBy = [
      'name' => 'asc'  
    ];
    
}
