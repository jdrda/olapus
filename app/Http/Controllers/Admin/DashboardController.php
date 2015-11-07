<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    /**
     * Module basic path
     */
    protected $moduleBasicRoute = 'admin.dashboard';
    
    /**
     * View basic path
     */
    protected $moduleBasicTemplatePath = 'admin.modules.user';
    
    /**
     * Constructor
     */
    public function __construct() {
        View::share('moduleBasicRoute', $this->moduleBasicRoute);
        View::share('moduleBasicTemplatePath', $this->moduleBasicTemplatePath);
        
        /**
         * Module name for blade
         */
        $temp = explode('.', $this->moduleBasicRoute);
        View::share('moduleNameBlade', $temp[0]."_module_".$temp[1]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('admin.modules.dashboard.index');
    }

}
