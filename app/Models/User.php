<?php
/**
 * User module model
 * 
 * Model for module User
 * 
 * @category Model
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** Allowed relation filters for the administration. */
    protected $adminFilters = [
        'role' => ['relation' => 'roles', 'label' => 'admin_filters.user_group'],
    ];

    use HasFactory, Notifiable, SoftDeletes, HasRoles, AdminModelTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'activation_code',
        'active',
        'fname',
        'mname',
        'sname',
        'phone',
        'fax',
        'mobile',
        'im',
        'street',
        'hno',
        'city',
        'zip',
        'state',
        'country',
        'da_street',
        'da_hno',
        'da_city',
        'da_zip',
        'da_state',
        'da_country',
        'notice',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * Columns to exclude from index
     * 
     * @var array 
     */
    protected $excludedFromIndex = ['password'];
    
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
        'email' => [
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
      'id' => 'desc'  
    ];
    
    /**
     * Articles link
     * 
     * @return object
     */
    public function articles(){
        
        return $this->hasMany(Article::class, 'user_id');
    }

    /**
     * Process relationships
     * 
     * @param query $query
     * @return query
     */
    public function scopeRelationships($query){
        
        return $query->with('roles');
    }
}
