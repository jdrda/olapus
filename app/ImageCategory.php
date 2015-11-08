<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageCategory extends Model
{
    use SoftDeletes, AdminModelTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'imagecategory';
    
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
    protected $fillable = ['name', 'description', 'color'];

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
     */
    protected $defaultOrderBy = [
      'name' => 'asc'  
    ];
    
    /**
     * Image link
     * 
     * @return type
     */
    public function image(){
        
        return $this->hasMany('App\Image');
    }
}
