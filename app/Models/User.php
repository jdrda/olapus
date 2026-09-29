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
        'company_name',
        'contact_name',
        'contact_email',
        'contact_phone',
        'company_id',
        'vat_id',
        'website',
        'customer_number',
        'external_id',
        'locale',
        'timezone',
        'billing_name',
        'billing_contact_name',
        'billing_street',
        'billing_house_number',
        'billing_city',
        'billing_zip',
        'billing_state',
        'billing_country',
        'billing_email',
        'billing_phone',
        'shipping_name',
        'shipping_contact_name',
        'shipping_street',
        'shipping_house_number',
        'shipping_city',
        'shipping_zip',
        'shipping_state',
        'shipping_country',
        'shipping_email',
        'shipping_phone',
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
        'company_name' => [
            'operator' => 'LIKE',
            'prefix' => '%',
            'sufix' => '%'
        ],
        'contact_name' => [
            'operator' => 'LIKE',
            'prefix' => '%',
            'sufix' => '%'
        ],
        'company_id' => [
            'operator' => 'LIKE',
            'prefix' => '%',
            'sufix' => '%'
        ],
        'vat_id' => [
            'operator' => 'LIKE',
            'prefix' => '%',
            'sufix' => '%'
        ],
        'customer_number' => [
            'operator' => 'LIKE',
            'prefix' => '%',
            'sufix' => '%'
        ],
        'external_id' => [
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
