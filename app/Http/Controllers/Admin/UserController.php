<?php
/**
 * User module controller
 * 
 * Controller for module User
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
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends AdminModuleController
{
    
    /**
     * Validation rules
     * 
     * @var array
     */
    protected $arValidationArray = [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|confirmed|min:6'];

    /**
     * Store function override
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        /**
         * Validate input
         */
        $this->validate($request, $this->arValidationArray);
        
        /**
         * Create row
         */
        $inputsToSave = array();
        foreach ($this->arValidationArray as $name => $value){
            $inputsToSave[$name] = $request[$name];
        }
        
        /**
         * Create row
         */
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        
        /**
         * Redirect to index
         */
        return redirect(route($this->moduleBasicRoute . '.index'));
    }

    /**
     * Update function override
     *
     * @param  Request  $request
     * @param  integer  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        /**
         * Validate input
         */
        $this->validate($request, [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users,email_address,'.$id,
                    'password' => 'required|confirmed|min:6']);
        
        /**
         * Get the row
         */
        $arResults = User::find($id);
        
        /**
         * Row does not exist - redirect
         */
        if($arResults == FALSE){
            
            return redirect(route($this->moduleBasicRoute . '.index'))->withInput()->withErrors(['edit' => trans('validation.row_not_exist')]);
        }
        
        /**
         * Set updated values
         */
        $arResults->name = $request['name'];
        $arResults->email = $request['email'];
      
        /**
         * Possible password change
         */
        if($request['password'] != '######'){
            $arResults->password  = Hash::make($request['password']);
        }
        
        /**
         * Save the changes
         */
        $arResults->save();
        
        /**
         * Return to index
         */
        return redirect(route($this->moduleBasicRoute . '.index'));
    }
}
