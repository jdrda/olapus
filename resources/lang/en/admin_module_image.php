<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Images Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for login page
    |
    */
    
    'name' => 'Images',
    'description' => 'Manage pictures',
    'delete_row_confirmation' => 'Are you sure you want to delete image called',
    'fields' => [
        'id' => 'ID',
        'name' => 'Name',
        'description' => 'Description',
        'alt' => 'Alternative name (ALT attribute)',
        'url' => 'Friendly URL',
        'image' => 'Image file',
        'category' => 'Category',
        'created_at' => 'Create at',
        'updated_at' => 'Update at',
        'deleted_at' => 'Deleted at',
    ],
    'action' => 'Action',
    'get_url' => 'Get URL',
    'get_info' => 'Get info',
    'detail_modal' => [
        'width' => 'Width',
        'height' => 'Height',
        'px'    => 'px',
        'size' => 'Size',
        'type'  => 'Type',
        'original_name'  => 'Original name',
    ],
    'url_modal' => [
        'url_of' => 'URL of',
        'instruction' => 'Press CTRL + C to copy the URL',
        'subinstruction'    => '(then ESCAPE or click x on the top right cortner to close this window)',
    ],
];
