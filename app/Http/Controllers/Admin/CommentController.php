<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends AdminModuleController {
    
    /**
     * Constructor
     */
    public function __construct(Request $request) {
        parent::__construct();
        
        /**
         * Add lookup tables
         */
        $this->middleware('add.lookup.tables:CommentStatus', ['only' => ['index','create','edit']]);

    }

    /**
     * Validation rules
     */
    protected $arValidationArray = [
        'name' => 'max:255',
        'headline' => 'max:255|required',
        'text' => 'max:1000',
        'url' => 'max:255',
        'email' => 'max:255',
    ];

    /**
     * Associate relationships to other table
     */
    public function associateRelationships($object, Request $request) {
        
        /**
         * Validate commentstatus ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
            'commentstatus_id' => 'required|integer|min:1|exists:commentstatus,id',
        ]);

        if ($validator->fails()) {

            $object->commentstatuses()->associate(1);
        }
        else{

            $object->commentstatuses()->associate($request->input('commentstatus_id'));
        }

        /**
         * Validate article ID, if failed set to actual authorized article
         */
        $validator = Validator::make($request->all(), [
                    'article_id' => 'required|integer|min:1|exists:articles,id',
        ]);
        

        if ($validator->fails()) {

            $object->articles()->associate(Auth::article()->id);
            
        } else {

            $object->articles()->associate($request->input('article_id'));
        }
        
        /**
         * Validate page ID, if failed set to actual authorized page
         */
        $validator = Validator::make($request->all(), [
                    'page_id' => 'required|integer|min:1|exists:pages,id',
        ]);
        

        if ($validator->fails()) {

            $object->pages()->associate(Auth::page()->id);
            
        } else {

            $object->pages()->associate($request->input('page_id'));
        }

       
    }

}
