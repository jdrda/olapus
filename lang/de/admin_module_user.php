<?php

return [
    'name' => 'Benutzer',
    'description' => 'Benutzer verwalten',
    'delete_row_confirmation' => 'Möchten Sie den Benutzer wirklich löschen?',
    'fields' => [
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'E-Mail',
        'password' => 'Passwort',
        'password_again' => 'Passwort wiederholen',
        'roles' => 'Benutzergruppen',
        'created_at' => 'Erstellt',
        'updated_at' => 'Aktualisiert',
        'deleted_at' => 'Gelöscht',
    ],
];
