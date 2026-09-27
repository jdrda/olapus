<?php

return [
    'name' => 'User groups',
    'description' => 'Manage user groups',
    'delete_row_confirmation' => 'Are you sure you want to delete the user group?',
    'fields' => [
        'id' => 'ID',
        'name' => 'Name',
        'name_help' => 'Internal Spatie role identifier (for example admin, editor).',
        'description' => 'Description',
        'users' => 'Users',
        'updated_at' => 'Updated',
    ],
];
