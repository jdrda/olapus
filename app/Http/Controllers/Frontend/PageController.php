<?php
/**
 * Page module controller
 * 
 * Controller for displaying pages
 * 
 * @category Controller
 * @subpackage Backend
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Frontend;


use App\Application;
use App\Campaign;
use App\Payment;
use App\Transaction;
use App\User;
use App\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use DB;
use Carbon\Carbon;
use Cache;



class PageController extends FrontendController
{

    /**
     * Get homepage
     *
     * @param Request $request
     * @return View|void
     */
    public function getHomepage(Request $request){

        /**
         * Load data
         */
        $this->_arViewData = $this->_loadPageByUrl('/');

        /**
         * Number of users
         */
        $this->_arViewData['users_no'] = User::count() + 135000;

        /**
         * Number of campaigns
         */
        $this->_arViewData['campaigns_no'] = Campaign::count();

        /**
         * Number of payments total
         */
        $this->_arViewData['payments_no_total'] = Payment::sum('amount');

        /**
         * Number of payments last month
         */
        $this->_arViewData['payments_no_last_month'] = Payment::where('created_at', '>=', Carbon::now()->subMonth())->count();

        /**
         * Return view
         */
        return $this->_showViewOr404('frontend.homepage');
    }

    /**
     * Get contact
     *
     * @param Request $request
     * @return View|void
     */
    public function getContact(Request $request){

        /**
         * Clear sessions
         */
        $this->_resetSessionFields($request);

        /**
         * Load page
         */
        $this->_arViewData = $this->_loadPageByUrl('kontaktujte-nas');

        /**
         * Return view
         */
        return $this->_showViewOr404('frontend.contact');
    }

    /**
     * Send e-mail to DB
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function registerEmail(Request $request){

        /**
         * Validate
         */
        if(env('RECAPTCHA_ENABLED') == 1){
            $this->validate($request, [
                'email' => 'email|required|max:255',
                'g-recaptcha-response' => 'required|recaptcha'
            ]);
        }
        else {

            $this->validate($request, [
                'email' => 'email|required|max:255'
            ]);
        }

        /**
         * User data
         */
        $ip = $request->ip();
        $email = $request->email;

        /**
         * TEMPORARY
         */
        //DB::table('users')->where('email', $email)->delete();

        /**
         * Check if user exists
         */
        $user = User::where('email', $email)->orWhere('ip', $ip)->first();
        $login = NULL;
        $password = NULL;
        $activation_code = base64_encode(Hash::make($email . time()));

        /**
         * User not exists
         */
        if(empty($user)){

            $login = $email;
            $password = str_random(10);

            $user = new User();
            $user->email = $login;
            $user->name = '';
            $user->password = Hash::make($password);
            $user->activation_code = $activation_code;
            $user->ip = $ip;
            $user->usergroup_id = 2;

            /**
             * Save recommendation
             */
            if(Cache::has('recommendation_id')){

                $recommendation_id = Cache::get('recommendation_id');

                /**
                 * Delete
                 */
                Cache::forget('recommendation_id');

                /**
                 * Check if recommendation was not used
                 */
                $test = User::where('recommendation_id', $recommendation_id)->first();


                /**
                 * If not , add to user
                 */
                if(empty($test)){
                    $user->recommendation_id = $recommendation_id;

                }
            }

            $user->save();

            /**
             * Send e-mail
             */
            Mail::send(['emails.html.registration', 'emails.plain.registration'], [
                'user' => $user,
                'login' => $login,
                'password' => $password,
                'activation_code' => $activation_code

            ], function ($message) {
                $message->from(env('MAIL_FROM_EMAIL', 'info@surimail.cz'), env('MAIL_FROM_NAME', 'Surimail.cz'));
                $message->to(request('email'), request('email'));
                $message->subject('Registrace na serveru Surimail.cz');

            });

            $request->session()->flash('success', 'Děkujeme za registraci! Další instrukce naleznete ve Vašem e-mailu!');
        }

        /**
         * User exists - error
         */
        else{

            $request->session()->flash('custom_error', 'Zadaný e-mail nebo IP již existuje');
        }

        return redirect(route('frontend.homepage.index'));
    }

    /**
     * Check activation code
     *
     * @param $email
     * @param $activationCode
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkActivationCode($email, $activationCode, Request $request){

        /**
         * Check if user exists
         */
        $user = User::where('email', $email)->where('activation_code', $activationCode)->first();

        /**
         * User not exists or code is not valid
         */
        if(empty($user)){
            $request->session()->flash('custom_error', 'Zadaný e-mail neexistuje nebo je neplatný aktivační kód');
        }

        /**
         * User exists - activate him
         */
        else{
            $user->active = true;
            $user->activation_code = str_random(16);
            $user->save();

            /**
             * Add reward for registration
             */
            $rewardsCount = Transaction::where('transactionstatus_id', 5)->where('user_id', $user->id)->count();
            if($rewardsCount < 1){

                /**
                 * Save transaction
                 */
                $this->_saveTransation(5, $user->id, $this->_getSettings(6));
            }

            /**
             * Add reward for recommedation
             */

            if(!empty($user->recomendation_id) ){

                $recommendation = Recommendation::find($user->reccomendation_id);

                if(!empty($recommendation)){

                   /**
                    * Check transaction
                    */
                    $transactionCount = Transaction::where('recommendation_id', $recommendation->id)->count();

                    if($transactionCount < 1){

                        /**
                         * Find user and save money
                         */
                        $rUser = User::find($recommendation->user_id);

                        if(!empty($rUser)){

                            $this->_saveTransation(2, $rUser->id, $this->_getSettings(2));
                        }
                    }
                }
            }

            $request->session()->flash('success', 'Gratulujeme, váš účet byl úspěšně aktivován. Přihlásit se můžete zde: '
            . '<br><a href="'.route('admin.dashboard.index').'">'.route('admin.dashboard.index').'</a>');

            /**
             * Save reward
             */
            if($user->recommendation_id != null && $user->recommendation_id > 0){

                /**
                 * Load recommendation
                 */
                $recommendation = Recommendation::find($user->recommendation_id);

                $this->_saveTransation(2, $recommendation->user_id, $this->_getSettings(2), null, null, $user->recommendation_id);
            }
        }

        return redirect(route('frontend.homepage.index'));
    }

    /**
     * Get blank page
     *
     * @param $pageUrl
     * @param Request $request
     * @return View|void
     */
    public function index($pageUrl, Request $request){

        /**
         * Clear sessions
         */
        $this->_resetSessionFields($request);

        /**
         * Load page
         */
        $this->_arViewData = $this->_loadPageByUrl($pageUrl);

        /**
         * Return view
         */
        return $this->_showViewOr404('frontend.blank');
    }

    /**
     * Check recommendation
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkRecommmendation($id, Request $request){

        /**
         * @todo Use validator
         */
        if(is_numeric($id)){

            $recommendation = Recommendation::find($id);

            /**
             * If exists, than remember
             */
            if(!empty($recommendation)){
                Cache::forever('recommendation_id', $id);
            }
        }

        return redirect(route('frontend.homepage.index'));
    }

}
