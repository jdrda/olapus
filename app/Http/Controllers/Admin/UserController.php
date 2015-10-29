<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    /**
     * Validation rules
     */
    protected $arValidationArray = [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|confirmed|min:6'];
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * Get the rows
         */
        $arResults = User::orderBy('id', 'desc')->paginate(env('ADMIN_PAGINATE'));
        
        /**
         * Return page
         */
        return view('admin.modules.user.index', ['results' => $arResults]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /**
         * Return page
         */
        return view('admin.modules.user.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        
        /**
         * Redirect to index
         */
        return redirect('admin/user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /**
         * Get the row
         */
        $arResults = User::find($id);
        
        /**
         * Row does not exist - redirect
         */
        if($arResults == FALSE){
            
            return redirect("admin/user")->withInput()->withErrors(['edit' => trans('validation.row_not_exist')]);
        }
        
        /**
         * Return page
         */    
        return view('admin.modules.user.create_edit', ['results' => $arResults]);
        
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * Validate input
         */
        $this->validate($request, $this->arValidationArray);
        
        /**
         * Get the row
         */
        $arResults = User::find($id);
        
        /**
         * Row does not exist - redirect
         */
        if($arResults == FALSE){
            
            return redirect("admin/user")->withInput()->withErrors(['edit' => trans('validation.row_not_exist')]);
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
        return redirect('admin/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /**
         * Delete the user
         */
        User::destroy($id);
        
        /**
         * Redirect to index
         */
        return redirect('admin/user');
    }

}
