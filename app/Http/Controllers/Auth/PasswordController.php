<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use \Illuminate\Http\Request;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function prePostEmail(Request $request){
        
        /**
         * Add recaptcha
         */
        if(env('RECAPTCHA_ENABLED') == 1){
            $this->validate($request, ['g-recaptcha-response' => 'required|recaptcha']);
        }
        
        return $this->postEmail($request);
    }
    
    public function prePostReset(Request $request){
        
        /**
         * Add recaptcha
         */
        if(env('RECAPTCHA_ENABLED') == 1){
            $this->validate($request, ['g-recaptcha-response' => 'required|recaptcha']);
        }
        
        return $this->postReset($request);
    }
    
}
