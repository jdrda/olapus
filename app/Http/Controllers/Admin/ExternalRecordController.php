<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ExternalRecordController extends AdminModuleController
{
    protected $arValidationArray = [
        'name' => 'required|string|max:255',
        'source_id' => 'required|integer|min:1|max:2147483647',
        'external_id' => 'required|integer|min:1',
    ];

    protected function validationRules(Model $model): array
    {
        $rules = parent::validationRules($model);
        $source = request('source_id');
        $rules['external_id'] = ['required', 'integer', 'min:1',
            Rule::unique('external_records', 'external_id')
                ->where('source_id', is_scalar($source) ? $source : null)->ignore($model),
        ];
        return $rules;
    }
}
