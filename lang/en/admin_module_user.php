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
        'password_leave_empty' => 'Leave blank when editing if you do not want to change the password.',
        'roles' => 'User groups',
        'created_at' => 'Created',
        'updated_at' => 'Updated',
        'deleted_at' => 'Deleted',
    ],
];
