<?php

return [
    'name' => 'Utilizadores',
    'description' => 'Gerir utilizadores',
    'delete_row_confirmation' => 'Tem a certeza de que pretende eliminar o utilizador',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nome',
        'email' => 'E-mail',
        'password' => 'Palavra-passe',
        'password_again' => 'Repetir palavra-passe',
        'password_leave_empty' => 'Deixe em branco ao editar se não quiser alterar a palavra-passe.',
        'roles' => 'Grupos de utilizadores',
        'created_at' => 'Criado',
        'updated_at' => 'Atualizado',
        'deleted_at' => 'Eliminado',
    ],
];
