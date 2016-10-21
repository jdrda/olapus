<?php
/**
 * Frontend module controller
 * 
 * Basic parent controller for frontend functions, controller is not ready yet
 * 
 * @category Controller
 * @subpackage Frontend
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use App\Settings;
use Illuminate\Support\Facades\View;
use App\Page;
use App\Transaction;
use App\User;



class FrontendController extends Controller
{
    /**
     * Data for view
     *
     * @var array
     */
    protected $_arViewData = [];

    protected $_arViewGeneratedData = [];

    /**
     * Check if view exists, if so - return, if not throw 404
     *
     * @param string $strView
     * @return View|void
     */
    protected function _showViewOr404($strView){


        if (view()->exists($strView)) {
            return view($strView, $this->_arViewData);
        }
        else{
            return abort(404);
        }
    }

    /**
     * Save all inputs to session
     * @param $request
     */
    protected function _saveSessionFields($request){

        foreach ($request->all() as $key=>$value){
            $arData[$key] = $value;
        }

        $request->session()->put('frontend_fields', $arData);
    }

    /**
     * Reset all session fields
     */
    protected function _resetSessionFields($request){

        $request->session()->forget('frontend_fields');
    }

    /**
     * Calculate price
     *
     * @param $request
     */
    protected function _calculatePrice($request){

        return 0;
    }

    /**
     * Get frontend fields
     *
     * @param $request
     * @return mixed
     */
    protected function _getSessionFields($request){

        $fields = $request->session()->get('frontend_fields');

        if(isset($request->id)){
            //$fields['price'] = $this->_calculatePrice($request);
        }

        if(isset($fields['city'])) {
            $arCityInfo = explode('|', $fields['city']);

            $fields['citypart'] = @trim($arCityInfo[0]);
            $fields['citymain'] = @trim($arCityInfo[1]);
            $fields['county'] = @trim($arCityInfo[2]);
            $fields['zip'] = @trim($arCityInfo[3]);
        }

        $this->_arViewData['fields'] = $fields;
    }

    /**
     * Load page by URL
     *
     * @param $url
     * @return mixed
     */
    protected function _loadPageByUrl($url){

        return Page::where('url', $url)->firstOrFail();
    }

    /**
     * Load page by id
     *
     * @param $id
     * @return mixed
     */
    protected function _loadPageById($id){
        return Page::findOrFail($id);
    }

    protected function _getSettings($id){

        $settings = Settings::find($id);

        if(!empty($settings)){
            return $settings['value'];
        }
        else{
            return NULL;
        }
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
}
