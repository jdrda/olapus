<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;

/**
 * Description of AdminModuleTrait
 *
 * @author Uzivatel
 */
class AdminModuleController extends Controller{

    /**
     * Module name
     */
    protected $moduleName;

    /**
     * Module basic path
     */
    protected $moduleBasicRoute;

    /**
     * View basic path
     */
    protected $moduleBasicTemplatePath;

    /**
     * Model Class
     */
    protected $modelClass;

    /**
     * Rows to paginate
     * 
     * @var type 
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
     * @var type 
     */
    protected $hiddenFieldsOnAction = array();
    
   /**
    * Binary fields to exclude from update
    * 
    * @var type 
    */
    protected $binaryFields = array();
    
    /**
     * Custom view
     * 
     * @var type 
     */
    protected $customView = NULL;

    /**
     * Constructor
     */
    public function __construct() {

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
         * Model
         */
        $this->modelClass = '\\App\\' . $this->moduleName;

        /**
         * Set variables
         */
        $this->moduleBasicRoute = 'admin.' . lcfirst($this->moduleName);
        $this->moduleBasicTemplatePath = 'admin.modules.' . strtolower($this->moduleName);

        /**
         * Global templates
         */
        View::share('moduleBasicRoute', $this->moduleBasicRoute);
        View::share('moduleBasicTemplatePath', $this->moduleBasicTemplatePath);

        /**
         * Module name for blade
         */
        $temp = explode('.', $this->moduleBasicRoute);
        View::share('moduleNameBlade', strtolower($temp[0] . "_module_" . $temp[1]));

    }
    
    /**
     * Save media to storage
     */
    public function saveMediaToStorage($object, $request, $update = FALSE){
        
        return FALSE;
    }

    /**
     * Get pagination rows
     */
    public function getRowsToPaginate() {

        if ($this->paginateRows == NULL) {

            return env('ADMIN_PAGINATE', 10);
        }
    }
    
    /**
     * Associate relationships to other table
     */
    public function associateRelationships($object, Request $request){
        
    }
    
    /**
     * Associate relationships to other table, where ID if objectmust be present
     */
    public function associateRelationshipsWithID($object, Request $request){
        
    }
    
    /**
     * Reset cache 
     */
    public function resetCache($object){
        
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    /*public function render($request, Exception $e) {
        if ($e instanceof CustomException) {
            
            return response()->view('errors.custom', [], 500);
        }

        return parent::render($request, $e);
    }*/

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        

        /**
         * Handle saved settings
         */
        $redirectRoute = resetSaveIndexParameters($this->moduleBasicRoute);
        if ($redirectRoute !== FALSE) {

            return redirect($redirectRoute);
        }

        /**
         * Get the rows
         */
        $modelClass = $this->modelClass;
        $arResults = $modelClass::where(function($query) {
                    $query->fulltextAllColumns();
                })->relationships()->orderByColumns()->excludeFromIndex()
                        ->externalTablesFilter()->paginate($this->getRowsToPaginate());
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
        return view($view, array_merge(['results' => $arResults]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
        $inputsToSave = array();
        foreach ($this->arValidationArray as $name => $value) {

            //$inputsToSave[$name] = $request->input($name);
            $object->{$name} = $request->input($name);
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
         * Redirect to index
         */
        return redirect(route($this->moduleBasicRoute . '.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
        $modelClass = $this->modelClass;

        /**
         * Get the row
         */
        $arResults = $modelClass::where('id', $id)->relationships()->excludeFromFind()->get();
        $arResults = $arResults[0];
    
        /**
         * Row does not exist - redirect
         */
        if (count($arResults) == 0) {

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
    
        /**
         * Return page
         */
        return view($view, ['results' => $arResults]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
             * @todo Delete
             */
            if(in_array($name, $this->binaryFields)){
 
                /**if($request->has($name)){
                    $this->arValidationArray[$name] = $value . ',' . $id;
                }**/
            }
            else{
                
                /**
                * Empty exception
                */
               if (empty($request->input($name)) == FALSE) {
                   $arResults->$name = $request->input($name);
               }
               
               else{
                   
                   /**
                    * Numeric zero ?
                    */
                   if(@is_numeric($request->input($name)) == TRUE){
                       
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
         * Return to index
         */
        return redirect(route($this->moduleBasicRoute . '.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
