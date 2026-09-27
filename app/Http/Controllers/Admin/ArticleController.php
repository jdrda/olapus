<?php
/**
 * Article module controller
 * 
 * Controller for module Article
 * 
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ArticleController extends AdminModuleController {
    protected $relationFields = ['articlecategory_id' => 'articlecategories'];


    /**
     * Contructor
     * 
     * @param Request $request
     */
    public function __construct(Request $request) {
        parent::__construct();

        /**
         * Add lookup tables
         */
        $this->middleware('add.lookup.tables:User', ['only' => ['create', 'edit']]);
        $this->middleware('add.lookup.tables:ArticleCategory', ['only' => ['create', 'edit']]);
        $this->middleware('add.published.at', ['only' => ['store', 'update']]);

    }

    /**
     * Validation rules
     * 
     * @var array
     */
    protected $arValidationArray = [
        'name' => 'required|max:255|unique:article,name',
        'meta_title' => 'max:255',
        'meta_description' => 'max:255',
        'meta_keywords' => 'max:255',
        'text' => 'max:1000000',
        'url' => 'required|max:255|unique:article,url',
        'author_name' => 'max:255',
        'published_at' => 'nullable|date',
    ];

    /**
     * Associate relationships to other table
     * 
     * @param object $object
     * @param Request $request
     */
    public function associateRelationships($object, Request $request) {

        /**
         * Validate user ID, if failed set to actual authorized user
         */
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|integer|min:1|exists:users,id',
        ]);
        
        /**
         * Validator fails - save current logged in user
         */
        if ($validator->fails()) {

            $object->users()->associate(Auth::user()->id);   
        } 
        /**
         * Validator OK - save it
         */
        else {

            $object->users()->associate($request->input('user_id'));
        }
    }
}
