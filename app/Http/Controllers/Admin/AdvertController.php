<?php
/**
 * Advert module controller
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

class AdvertController extends AdminModuleController
{
    protected $relationFields = ['advertlocation_id' => 'advertlocations'];

    public function __construct(Request $request)
    {
        parent::__construct();

        $this->middleware('add.lookup.tables:AdvertLocation', ['only' => ['create', 'edit']]);
    }

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $arValidationArray = [
        'name' => 'required|max:255|unique:advert,name',
        'caption' => 'max:255',
        'text' => 'max:1000000',
        'link_url' => 'max:255',
        'link_title' => 'max:255',
        'position' => 'required|integer',
    ];


}
