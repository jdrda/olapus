<?php

return [
    'name' => 'Utenti',
    'description' => 'Gestisci utenti',
    'delete_row_confirmation' => 'Sei sicuro di voler eliminare l’utente',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nome',
        'email' => 'E-mail',
        'password' => 'Password',
        'password_again' => 'Ripeti password',
        'password_leave_empty' => 'Lascia vuoto durante la modifica se non vuoi cambiare la password.',
        'roles' => 'Gruppi utenti',
        'created_at' => 'Creato',
        'updated_at' => 'Aggiornato',
        'deleted_at' => 'Eliminato',
    ],
];
