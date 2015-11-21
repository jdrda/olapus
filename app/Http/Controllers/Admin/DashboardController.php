<?php

namespace App\Http\Controllers\Admin;

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
     * Google analytics data
     */
    protected $gaData = [];
    
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
        /**
         *  Get Google Analytics values 
         */
        if(env('ANALYTICS_ENABLED') == 1){
           
           $ga = $this->getGAValues();
        }
        else{
           
           $ga = [];
       }
       
       $statistics = [
           'ga' => $ga
       ];
        
        return view('admin.modules.dashboard.index', ['statistics' => $statistics]);
    }
    
    /**
     * Get visitors or pageviews from Google API
     * 
     * @param type $days
     * @param type $type
     * @return type
     */
    public function gaGetVisitorsPageviews($days = 7, $type = 'visitors'){
        
        $data = \LaravelAnalytics::getVisitorsAndPageViews($days);
        
        $total = 0;
        
        foreach ($data as $value) {
           
           $total += $value[$type];
       }
       
       return $total;
    }
    
    /**
     * Get percent difference between values
     * 
     * @param type $lastValue
     * @param type $thisValue
     * @param type $round
     * @return type
     */
    public function getPercentDifference($lastValue, $thisValue, $round = 2){
        
        if($thisValue > 0 && $lastValue > 0){
            return @round((($thisValue / ($lastValue / 100)) - 100), $round);
        }
        else{
            return 0;
        }
    }
    
    /**
     * Get Google Analytics values
     * 
     * @return type
     */
    public function getGAValues(){
        
        /**
        * Visitors and pageviews this week
        */
       $visitorsThisWeek = $this->gaGetVisitorsPageviews(7, 'visitors');
       $pageviewsThisWeek = $this->gaGetVisitorsPageviews(7, 'pageViews');
       
       /**
        * Visitors and pageviews two weeks
        */
       $visitorsTwoWeeks = $this->gaGetVisitorsPageviews(14, 'visitors');
       $pageviewsTwoWeeks = $this->gaGetVisitorsPageviews(14, 'pageViews');
       
       /**
        * Visitors and pageviews last week
        */
       $visitorsLastWeek = $visitorsTwoWeeks - $visitorsThisWeek;
       $pageviewsLastWeek = $pageviewsTwoWeeks - $pageviewsThisWeek;
       $visitorsPercentThisWeek = $this->getPercentDifference($visitorsLastWeek, $visitorsThisWeek);
       $pageviewsPercentThisWeek = $this->getPercentDifference($pageviewsLastWeek, $pageviewsThisWeek);
       
       /**
        * Visitors and pageviews this month
        */
       $visitorsThisMonth = $this->gaGetVisitorsPageviews(30, 'visitors');
       $pageviewsThisMonth = $this->gaGetVisitorsPageviews(30, 'pageViews');
       
       /**
        * Visitors and pageviews last month
        */
       $visitorsTwoMonths = $this->gaGetVisitorsPageviews(60, 'visitors');
       $pageviewsTwoMonths = $this->gaGetVisitorsPageviews(60, 'pageViews');
       
       /**
        * Visitors and pageviews last month
        */
       $visitorsLastMonth = $visitorsTwoMonths - $visitorsThisMonth;
       $pageviewsLastMonth = $pageviewsTwoMonths - $pageviewsThisMonth;
       $visitorsPercentThisMonth = $this->getPercentDifference($visitorsLastMonth, $visitorsThisMonth);
       $pageviewsPercentThisMonth = $this->getPercentDifference($pageviewsLastMonth, $pageviewsThisMonth);
       
       $ga = [
           'visitors_this_week' => $visitorsThisWeek,
           'visitors_last_week' => $visitorsLastWeek,
           'visitors_percent_this_week' => $visitorsPercentThisWeek,
           'pageviews_this_week' => $pageviewsThisWeek,
           'pageviews_last_week' => $pageviewsLastWeek,
           'pageviews_percent_this_week' => $pageviewsPercentThisWeek,
           
           'visitors_this_month' => $visitorsThisMonth,
           'visitors_last_month' => $visitorsLastMonth,
           'visitors_percent_this_month' => $visitorsPercentThisMonth,
           'pageviews_this_month' => $pageviewsThisMonth,
           'pageviews_last_month' => $pageviewsLastMonth,
           'pageviews_percent_this_month' => $pageviewsPercentThisMonth,
        ];
       
       return $ga;
    }
    

}
