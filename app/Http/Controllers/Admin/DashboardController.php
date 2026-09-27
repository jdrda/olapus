<?php
/**
 * Dashboard module controller.
 *
 * @category Controller
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    protected $moduleBasicRoute = 'admin.dashboard';

    protected $moduleBasicTemplatePath = 'admin.modules.dashboard';

    public function __construct()
    {
        View::share('moduleBasicRoute', $this->moduleBasicRoute);
        View::share('moduleBasicTemplatePath', $this->moduleBasicTemplatePath);

        $temp = explode('.', $this->moduleBasicRoute);
        View::share('moduleNameBlade', $temp[0] . '_module_' . $temp[1]);
    }

    public function index()
    {
        return view('admin.modules.dashboard.index', [
            'statistics' => [
                'ga' => [],
            ],
        ]);
    }
}
