<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Admin\VirtualFulltextController as VirtualFulltext;

class ArticleCategory extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'article_category';
    
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
    protected $fillable = ['name', 'color', 'article_id', 'meta_description', 
        'meta_keywords', 'text', 'url', 'image'];
}
