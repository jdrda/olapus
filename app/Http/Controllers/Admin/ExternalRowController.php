<?php

namespace App\Http\Controllers\Admin;

class ExternalRowController extends AdminModuleController
{
    protected $arValidationArray = [
        'name' => 'required|string|max:255',
        'external_record_id' => 'required|integer|min:1',
    ];

    // Virtual form field. associateAdminRelation resolves the scalar ID, then copies BOTH owner keys.
    protected $relationFields = ['external_record_id' => 'record'];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('add.lookup.tables:ExternalRecord', ['only' => ['create', 'edit']]);
    }
}
