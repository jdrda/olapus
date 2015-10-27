<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct() {
        ;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        print "ok";
        die;
    }

}
