<?php
/**
 * Admin module controller
 * 
 * Basic parent controller for admin functions
 * 
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

use App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Transaction;
use App\User;

class AdminModuleController extends Controller{

    /**
     * Module name
     * 
     * @var string 
     */
    protected $moduleName;

    /**
     * Module basic path
     * 
     * @var string
     */
    protected $moduleBasicRoute;

    /**
     * View basic path
     * 
     * @var string
     */
    protected $moduleBasicTemplatePath;

    /**
     * Model Class
     * 
     * @var string
     */
    protected $modelClass;

    /**
     * Rows to paginate
     * 
     * @var integer 
     */
    protected $paginateRows = NULL;
    
    /**
     * View variables to inject
     * 
     * @var array
     */
    protected $viewVariables = array();
    
    /**
     * Hidden fields for action
     * 
     * @var array 
     */
    protected $hiddenFieldsOnAction = array();
    
   /**
    * Binary fields to exclude from update
    * 
    * @var array 
    */
    protected $binaryFields = array();

    /**
     * Binary fields to exclude from update
     *
     * @var array
     */
    protected $dateTimeLocalFields = array();
    
    /**
     * Custom view
     * 
     * @var string 
     */
    protected $customView = NULL;

    /**
     * Constructor
     */
    public function __construct() {

        /**
         * Check if user is active
         */
       /* print_r(Auth::user());
        die;*/

        /**
         * Pagination handle
         */
        if ($this->paginateRows == NULL) {

            $this->paginateRows = env('ADMIN_PAGINATE', 10);
        }

        /**
         * Get module name
         */
        $temp = explode("\\", str_replace('Controller', '', get_called_class()));
        $this->moduleName = trim(last($temp));
        
        /**
         * Get model full name
         */
        $this->modelClass = '\\App\\' . $this->moduleName;

        /**
         * Set basic variables
         */
        $this->moduleBasicRoute = 'admin.' . lcfirst($this->moduleName);
        $this->moduleBasicTemplatePath = 'admin.modules.' . strtolower($this->moduleName);

        /**
         * Global template variables
         */
        View::share('moduleBasicRoute', $this->moduleBasicRoute);
        View::share('moduleBasicTemplatePath', $this->moduleBasicTemplatePath);
        View::share('moduleName', $this->moduleName);

        /**
         * Module name for blade
         */
        $temp = explode('.', $this->moduleBasicRoute);
        View::share('moduleNameBlade', strtolower($temp[0] . "_module_" . $temp[1]));

    }

    /**
     * Runs after store
     *
     * @param $request
     */
    protected function afterStore($request){

    }

    /**
     * Runs after update
     *
     * @param $request
     */
    protected function afterUpdate($request){

    }

    /**
     * Save transaction
     *
     * @param int $typeId
     * @param string $text
     * @param $userId
     * @param $amount
     */
    protected function _saveTransation($status_id = 1, $user_id, $amount,
                                       $campaign_id = null, $payment_id = null, $recommendation_id = null){

        /**
         * Save transaction
         */
        $transaction = new Transaction();
        $transaction->transactionstatus_id = $status_id;
        $transaction->user_id = $user_id;
        $transaction->amount = $amount;
        $transaction->campaign_id = $campaign_id;
        $transaction->payment_id = $payment_id;
        $transaction->recommendation_id = $recommendation_id;
        $transaction->save();

        /**
         * Update user wallet
         */
        $user = User::find($user_id);
        $user->wallet = $user->wallet + $amount;
        $user->save();
    }
    
    /**
     * Save media to storage
     * 
     * @param object $object
     * @param Request $request
     * @param boolean $update
     * @return boolean
     */
    public function saveMediaToStorage($object, $request, $update = FALSE){
        
        return FALSE;
    }
    
    /**
     * Get number of pagination rows
     * 
     * @return integer
     */
    public function getRowsToPaginate() {

        if ($this->paginateRows == NULL) {

            return env('ADMIN_PAGINATE', 10);
        }
        else{

            return $this->paginateRows;
        }
    }
    
    /**
     * Associate relationships to other table
     * 
     * @param object $object
     * @param Request $request
     */
    public function associateRelationships($object, Request $request){
        
    }
    
    /**
     * Associate relationships to other table, where ID if object must be present
     * 
     * @param object $object
     * @param Request $request
     */
    public function associateRelationshipsWithID($object, Request $request){
        
    }

    /**
     * Reset cache 
     * 
     * @param object $object
     */
    public function resetCache($object){
        
    }

    /**
     * Change ar result if necessary
     *
     * @param $arResult
     * @return mixed
     */
    public function changeEditResultField($arResult){
        return $arResult;
    }

    /**
     * Display a listing of the resource
     * 
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {

        /**
         * Handle saved settings
         */
        $redirectRoute = Helpers::resetSaveIndexParameters($this->moduleBasicRoute);
        if ($redirectRoute !== FALSE) {

            return redirect($redirectRoute);
        }

        /**
         * Get the rows
         */
        $modelClass = $this->modelClass;


        $arResults = $modelClass::where(function(Builder $query) {
                    $query->fulltextAllColumns();
                })->relationships()->orderByColumns()->excludeFromIndex()
                        ->externalTablesFilter()->paginate($this->getRowsToPaginate());

        /**
         * Get media
         */
        if(method_exists($arResults, 'getMedia')){
            $media = $arResults->getMedia();
        }
        else{
            $media = null;
        }
        
                
        /**
         * Choose the view
         */
        if(empty($this->customView['index']) == TRUE){
            $view = $this->moduleBasicTemplatePath . '.index';
        }
        else{
            $view = $this->customView;
        }

        /**
         * Return page
         */
        return view($view, ['results' => $arResults, 'media' => $media]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        
        /**
         * Choose the view
         */
        if(empty($this->customView['index']) == TRUE){
            $view = $this->moduleBasicTemplatePath . '.create_edit';
        }
        else{
            $view = $this->customView;
        }
        
        /**
         * Return page
         */
        return view($view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {

        /**
         * Change the validation array
         */
        foreach ($this->arValidationArray as $name => $value) {
            if(strpos($this->arValidationArray[$name], 'unique') > 0){
                $this->arValidationArray[$name] = $value . ',NULL,id,deleted_at,NULL';
            }
        }

        /**
         * Validate input
         */
        $this->validate($request, $this->arValidationArray);

        /**
         * Create new object
         */
        $modelClass = $this->modelClass;
        $object = new $modelClass();

        /**
         * Create row
         */
        foreach ($this->arValidationArray as $name => $value) {

            /**
             * Datetime
             */
            if(in_array($name, $this->dateTimeLocalFields)){

                $object->{$name} = str_replace('T', ' ', $request->input($name)). ':00';
            }
            else {

                /**
                 * Change to null if needed
                 */
                if(strlen($request->$name) < 1){
                    $object->{$name} = null;
                }
                else {
                    $object->{$name} = $request->input($name);
                }
            }


        }

        /**
         * Associate relationships
         */
        $this->associateRelationships($object, $request);
        
        /**
         * Save main object
         */
        $object->save();
        
        /**
         * Save media to storage
         */
        $this->saveMediaToStorage($object, $request);
        
        /**
         * Associate relatinships with ID
         */
        $this->associateRelationshipsWithID($object, $request);

        /**
         * After store
         */
        $this->afterStore($request);

        /**
         * Redirect to index
         */
        if(!empty($request->custom_route)){
            return redirect($request->custom_route);
        }
        else {
            return redirect(route($this->moduleBasicRoute . '.index'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
        $modelClass = $this->modelClass;

        /**
         * Get the row
         */
        $arResults = $modelClass::where('id', $id)->relationships()->excludeFromFind()->first();


        /**
         * Row does not exist - redirect
         */
        if (empty($arResults)) {

            return redirect(route($this->moduleBasicRoute . '.index'))->withInput()->withErrors(['edit' => trans('validation.row_not_exist')]);
        }

        /**
         * Set the put method for update
         */
        $arResults['_method'] = 'PUT';
        
        /**
         * Choose the view
         */
        if(empty($this->customView['index']) == TRUE){
            $view = $this->moduleBasicTemplatePath . '.create_edit';
        }
        else{
            $view = $this->customView;
        }

        $arResults = $this->changeEditResultField($arResults);
    
        /**
         * Return page
         */
        return view($view, ['results' => $arResults]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {


        /**
         * Change the validation array
         */
        foreach ($this->arValidationArray as $name => $value) {
            
            if(strpos($this->arValidationArray[$name], 'unique') > 0){
                $this->arValidationArray[$name] = $value . ','.$id.',id,deleted_at,NULL';
            }
        }

        /**
         * Validate input
         */
        $this->validate($request, $this->arValidationArray);
        
        /**
         * Get the row
         */
        $modelClass = $this->modelClass;
        $arResults = $modelClass::find($id);

        /**
         * Row does not exist - redirect
         */
        if ($arResults == FALSE) {

            return redirect(route($this->moduleBasicRoute . '.index'))->withInput()->withErrors(['edit' => trans('validation.row_not_exist')]);
        }
        
        /**
         * Reset cache
         */
        $this->resetCache($arResults);

        /**
         * Set updated values
         */
        foreach ($this->arValidationArray as $name => $value) {
            
            /**
             * Binary fields will not be updated if empty
             *
             */
            if(in_array($name, $this->binaryFields)){

            }
            else{
                
               /**
                * Empty exception
                */
               if (empty($request->input($name)) == FALSE) {

                   /**
                    * Datetime
                    */
                   if(in_array($name, $this->dateTimeLocalFields)){

                       $arResults->$name = str_replace('T', ' ', $request->input($name)). ':00';
                   }
                   else {
                       $arResults->$name = $request->input($name);
                   }
               }
               
               else{
                   
                   /**
                    * Numeric zero ?
                    */
                   if(isset($request->$name) && is_numeric($request->input($name)) == TRUE){
                       
                      $arResults->$name = $request->input($name);
                   }
                   
                   else{
                    $arResults->$name = NULL;
                   }
               }
            }

            
        }

        
        /**
         * Associate relationships
         */
        $this->associateRelationships($arResults, $request);
        
        /**
         * Save media to storage
         */
        if($this->saveMediaToStorage($arResults, $request, TRUE) == TRUE){
            
            // Update binary fields
            foreach ($this->binaryFields as $name => $value) {
                
                if (empty($request->$value) == FALSE) {
                   $arResults->$value = $request->$value;
                }
                else{
                    $arResults->$value = NULL;
                }
            }
        }

        /**
         * Save the changes
         */
        $arResults->save();
        
        /**
         * Associate relatinships with ID
         */
        $this->associateRelationshipsWithID($arResults, $request);

        /**
         * After update
         */
        $this->afterUpdate($request);

        /**
         * Redirect to index
         */
        if(!empty($request->custom_route)){
            return redirect($request->custom_route);
        }
        else {
            return redirect(route($this->moduleBasicRoute . '.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    public function destroy($id) {
        /**
         * Delete the setting
         */
        $modelClass = $this->modelClass;
        $modelClass::destroy($id);

        /**
         * Redirect to index
         */
        return redirect(route($this->moduleBasicRoute . '.index'));
    }

}
