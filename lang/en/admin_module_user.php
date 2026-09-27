<?php

return [
    'name' => 'Users',
    'description' => 'Manage users',
    'delete_row_confirmation' => 'Are you sure you want to delete the user',
    'fields' => [
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'password_again' => 'Password again',
        'roles' => 'User groups',
        'created_at' => 'Created',
        'updated_at' => 'Updated',
        'deleted_at' => 'Deleted',
    ],
];
