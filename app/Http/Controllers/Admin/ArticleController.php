<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends AdminModuleController {

    /**
     * Constructor
     */
    public function __construct(Request $request) {
        parent::__construct();

        /**
         * Add lookup tables
         */
        $this->middleware('add.lookup.tables:User', ['only' => ['create', 'edit']]);
        $this->middleware('add.lookup.tables:ArticleCategory', ['only' => ['create', 'edit']]);
        $this->middleware('add.published.at', ['only' => ['store', 'update']]);

        /**
         * Other tables
         */
        $this->middleware('media.image.select:Image', ['only' => ['create', 'edit']]);
    }

    /**
     * Validation rules
     */
    protected $arValidationArray = [
        'name' => 'required|max:255|unique:article',
        'meta_title' => 'max:255',
        'meta_description' => 'max:255',
        'meta_keywords' => 'max:255',
        'text' => 'max:1000000',
        'url' => 'required|max:255|unique:article',
        'author_name' => 'max:255',
        'published_at' => 'date',
    ];
    protected $arValidationArrayUpdateChange = [
        'name' => 'required|max:255|unique:article,name',
        'url' => 'required|max:255|unique:article,url',
    ];

    /**
     * Associate relationships to other table
     */
    public function associateRelationships($object, Request $request) {

        /**
         * Validate user ID, if failed set to actual authorized user
         */
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|integer|min:1|exists:users,id',
        ]);
        

        if ($validator->fails()) {

            $object->users()->associate(Auth::user()->id);
            
        } else {

            $object->users()->associate($request->input('user_id'));
        }

        /**
         * Validate image ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
                    'image_id' => 'integer|min:1|exists:image,id',
        ]);

        if ($validator->fails()) {

            // nothing to do
        } else {

            $object->images()->associate($request->input('image_id'));
        }
    }
    
    /**
     * Associate relationships to other table with ID
     */
    public function associateRelationshipsWithID($object, Request $request) {
        
        /**
         * Validate article category ID, if failed set to default
         */
        
        
        if($request->has('articlecategory_id')){
            
            $validIDs = [];
            
            
            foreach ($request->input('articlecategory_id') as $articlecategory_id) {

                $arrayForValidator = ['articlecategory_id' =>  $articlecategory_id];

                $validator = Validator::make($arrayForValidator, [
                            'articlecategory_id' => 'required|integer|min:1|exists:articlecategory,id',
                ]);

                if ($validator->fails()) {

                    /**
                     * Nothing to do
                     */
                } else {

                    $validIDs[] = $articlecategory_id;
                }
            }
            
            /**
             * Sync all to pivot
             */
            $object->articlecategories()->sync($validIDs);
        }
    }
    

}
