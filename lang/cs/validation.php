<?php
/**
 * Validator language file - Czech
 * 
 * Language translation for elements in validator in Czech language
 * 
 * @category Languages
 * @subpackage General
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

return [

    'accepted'             => 'Je potřeba potvrdit :attribute.',
    'active_url'           => ': attribute není platná adresa URL.',
    'after'                => ':attribute musí být po datu :date.',
    'alpha'                => ':attribute může obsahovat pouze písmena.',
    'alpha_dash'           => ':attribute může obsahovat pouze písmena, čísla, a pomlčky.',
    'alpha_num'            => ':attribute může obsahovat pouze písmena a čísla.',
    'array'                => ':attribute musí být pole.',
    'before'               => ':attribute musí být datum před :date.',
    'between'              => [
        'numeric' => ':attribute musí být v rozmezí :min a :max.',
        'file'    => ':attribute musí být v rozmezí :min a :max kilobajtů.',
        'string'  => ':attribute musí mít délku rozmezí :min a :max znaků.',
        'array'   => ':attribute musí být mezi :min a :max položkami.',
    ],
    'boolean'              => ':attribute musí mít hodnotu pravda nebo nepravda.',
    'confirmed'            => 'Potvrzení :attribute se neshoduje.',
    'date'                 => ':attribute není platným datem.',
    'date_format'          => ':attribute se neshoduje se správným formátem :format.',
    'different'            => ':attribute a :other se musí lišit.',
    'digits'               => ':attribute musí obsahovat :digits číslic.',
    'digits_between'       => ':attribute musí být v rozmezí :min a :max číslic.',
    'email'                => ':attribute musí být platná e-mailová adresa.',
    'exists'               => 'Vybraný :attribute je neplatný.',
    'filled'               => 'Pole :attribute je vyžadováno.',
    'image'                => ':attribute musí být obrázek.',
    'in'                   => 'Vybraný :attribute je neplatný.',
    'integer'              => ':attribute musí být celé číslo.',
    'ip'                   => ':attribute musí být platná IP adresa.',
    'json'                 => ': attribute musí být platný řetězec formátu JSON.',
    'max'                  => [
        'numeric' => ':attribute nesmí být větší než :max.',
        'file'    => ':attribute nesmí být větší než :max.',
        'string'  => ':attribute nesmí být větší než :max znaků.',
        'array'   => ':attribute nesmí obsahovat více než: max položek.',
    ],
    'mimes'                => ':attribute musí být soubor typu: :values.',
    'min'                  => [
        'numeric' => ':attribute musí být alespoň :min.',
        'file'    => ':attribute musí být dlouhý alespoň :min kb.',
        'string'  => ':attribute musí mít alespoň :min znaků.',
        'array'   => ':attribute musí mít alespoň :min položek.',
    ],
    'not_in'               => 'Vybraný :attribute je neplatný.',
    'numeric'              => ':attribute musí být číslo.',
    'regex'                => 'Formát :attribute je neplatný.',
    'required'             => 'Pole :attribute je vyžadováno.',
    'required_if'          => 'Pole :attribute je požadováno, když :other je :value.',
    'required_with'        => 'Pole :attribute je požadováno pokud :values je k dispozici.',
    'required_with_all'    => 'Pole :attribute je požadováno pokud :values je k dispozici.',
    'required_without'     => 'Pole :attribute je požadováno když :values není k dispozici.',
    'required_without_all' => ':attribute je nutný, pokud není zadaná žádná :values.',
    'same'                 => ':attribute a :other se musí shodovat.',
    'size'                 => [
        'numeric' => ':attribute must mít délku :size.',
        'file'    => ':attribute musí mít :size kB.',
        'string'  => ':attribute musí mít :size znaků.',
        'array'   => ':attribute musí obsahovat :size položek.',
    ],
    'string'               => ':attribute musí být textový řetězec.',
    'timezone'             => ':attribute musí být platná zóna.',
    'unique'               => ':attribute byl již použit.',
    'url'                  => 'Formát :attribute je neplatný.',
    
    'row_not_exist' => 'Řádek neexistuje',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'vlastní zpráva',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
