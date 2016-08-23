<?php
/**
 * Comment module controller
 * 
 * Controller for module Comment
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
use App\Comment;
use Illuminate\Support\Facades\Redirect;

class CommentController extends AdminModuleController {
    
    /**
     * Constructor
     * 
     * @param Request $request
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
     * 
     * @var array
     */
    protected $arValidationArray = [
        'name' => 'max:255',
        'headline' => 'max:255|required',
        'text' => 'max:1000',
        'url' => 'max:255',
        'email' => 'max:255',
        'rating' => 'integer|max:1|min:-1|required'
    ];

    /**
     * Associate relationships to other table
     * 
     * @param object $object
     * @param Request $request
     */
    public function associateRelationships($object, Request $request) {
        
        /**
         * Validate commentstatus ID, if failed set to default
         */
        $validator = Validator::make($request->all(), [
            'commentstatus_id' => 'required|integer|min:1|exists:commentstatus,id',
        ]);

        /**
         * Validator fails - try to set the valid comment id
         *
         */
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
        
        /**
         * Validator fails - nothing to do
         */
        if ($validator->fails()) {
            
        } 
        
        /**
         * Validator OK - save it
         */
        else {

            $object->articles()->associate($request->input('article_id'));
        }
        
        /**
         * Validate page ID, if failed set to actual authorized page
         */
        $validator = Validator::make($request->all(), [
                    'page_id' => 'required|integer|min:1|exists:page,id',
        ]);
        
        /**
         * Validator fails - nothing to do
         */
        if ($validator->fails()) {

            // nothing
            
        } 
        
        /**
         * Validator OK - save it
         */
        else {

            $object->pages()->associate($request->input('page_id'));
        }

    }
    
    /**
     * Approve comment
     * 
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function approve($id, Request $request){
        
        /**
         * Add ID to request
         */
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
             
        } 
        
        /**
         * OK, validate
         */
        else {
            
            $object = Comment::find($id);
            $object->commentstatus_id = 2;
            $object->save();
        }
        
         return Redirect::back();
       
    }

    /**
     * Mark as SPAM
     * 
     * @param integer $id
     * @param Request $request
     * @return Response
     */
    public function spam($id, Request $request){
        
        /**
         * Add ID to request
         */
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
       
        } 
        
        /**
         * OK, validate
         */
        else {
            
            $object = Comment::find($id);
            $object->commentstatus_id = 4;
            $object->save();
        }
        
         return Redirect::back();
    }
}
