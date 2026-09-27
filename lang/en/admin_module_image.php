<?php

return [
    'name' => 'Images',
    'description' => 'Manage images',
    'delete_row_confirmation' => 'Are you sure you want to delete the image named',
    'fields' => [
        'id' => 'ID',
        'name' => 'Name',
        'description' => 'Description',
        'alt' => 'Alternative text (ALT attribute)',
        'url' => 'URL',
        'image' => 'Image file',
        'category' => 'Category',
        'created_at' => 'Created',
        'updated_at' => 'Updated',
        'deleted_at' => 'Deleted',
    ],
    'action' => 'Action',
    'get_url' => 'Get URL',
    'get_info' => 'Details',
    'detail_modal' => [
        'width' => 'Width',
        'height' => 'Height',
        'px' => 'px',
        'size' => 'Size',
        'type' => 'Type',
        'original_name' => 'Original name',
    ],
    'url_modal' => [
        'url_of' => 'URL',
        'instruction' => 'Press CTRL + C to copy the URL',
        'subinstruction' => '(then press ESCAPE or click the x in the top-right corner to close this window)',
    ],
];
