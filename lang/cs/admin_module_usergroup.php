<?php

return [
    'name' => 'Uživatelské skupiny',
    'description' => 'Správa uživatelských skupin',
    'delete_row_confirmation' => 'Jste si jisti, že chcete odstranit uživatelskou skupinu?',
    'fields' => [
        'id' => 'ID',
        'name' => 'Název',
        'name_help' => 'Interní identifikátor role používaný Spatie (např. admin, editor).',
        'description' => 'Popis',
        'users' => 'Uživatelé',
        'updated_at' => 'Aktualizováno',
    ],
];
