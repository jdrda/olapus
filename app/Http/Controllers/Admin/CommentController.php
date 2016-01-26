<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Comment;
use Illuminate\Support\Facades\Redirect;

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
            
            /**
             * Auto approve ?
             */
            if(env('AUTO_APPROVE_COMMENTS', 0) == 1){
                
                $object->commentstatuses()->associate(2);
            }
            
            /**
             * Manual approve
             */
            else{
                $object->commentstatuses()->associate(1);
            }
            
        }
        else{

            $object->commentstatuses()->associate($request->input('commentstatus_id'));
        }

        /**
         * Validate article ID, if failed set to actual authorized article
         */
        $validator = Validator::make($request->all(), [
                    'article_id' => 'required|integer|min:1|exists:article,id',
        ]);
        

        if ($validator->fails()) {

            // nothing
            
        } else {

            $object->articles()->associate($request->input('article_id'));
        }
        
        /**
         * Validate page ID, if failed set to actual authorized page
         */
        $validator = Validator::make($request->all(), [
                    'page_id' => 'required|integer|min:1|exists:page,id',
        ]);
        

        if ($validator->fails()) {

            // nothing
            
        } else {

            $object->pages()->associate($request->input('page_id'));
        }

       
    }
    
    /**
     * Approve
     * 
     * @param type $id
     */
    public function approve($id, Request $request){
        
        $request->merge(array('id' => $id));
        
        /**
         * Validate comment ID
         */
        $validator = Validator::make($request->all(), [
                    'id' => 'required|integer|min:1|exists:comment,id',
        ]);
        
        /**
         * Failed - redirect to index
         */
        if ($validator->fails()) {
            
            // nothing
            
        } 
        
        /**
         * OK, validate
         */
        else {
            
            $object = Comment::find(1);
            $object->commentstatus_id = 2;
            $object->save();
        }
        
         return Redirect::back();
       
    }
    
    /**
     * SPAM
     * 
     * @param type $id
     */
    public function spam($id, Request $request){
        
        $request->merge(array('id' => $id));
        
        /**
         * Validate comment ID
         */
        $validator = Validator::make($request->all(), [
                    'id' => 'required|integer|min:1|exists:comment,id',
        ]);
        
        /**
         * Failed - redirect to index
         */
        if ($validator->fails()) {
            
            // nothing
            
        } 
        
        /**
         * OK, validate
         */
        else {
            
            $object = Comment::find(1);
            $object->commentstatus_id = 4;
            $object->save();
        }
        
         return Redirect::back();
       
    }

}
