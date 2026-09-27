<?php

return [
    'name' => 'Uživatelé',
    'description' => 'Správa uživatelů',
    'delete_row_confirmation' => 'Jste si jisti že chcete odstranit uživatele',
    'fields' => [
        'id' => 'ID',
        'name' => 'Jméno',
        'email' => 'E-mail',
        'password' => 'Heslo',
        'password_again' => 'Heslo znovu',
        'password_leave_empty' => 'Při úpravě ponechte prázdné, pokud heslo nechcete změnit.',
        'roles' => 'Uživatelské skupiny',
        'created_at' => 'Vytvořeno',
        'updated_at' => 'Aktualizováno',
        'deleted_at' => 'Odstraněno',
    ],
];
