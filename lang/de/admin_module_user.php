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
        'password_leave_empty' => 'Bei der Bearbeitung leer lassen, wenn das Passwort nicht geändert werden soll.',
        'roles' => 'Benutzergruppen',
        'created_at' => 'Erstellt',
        'updated_at' => 'Aktualisiert',
        'deleted_at' => 'Gelöscht',
    ],
];
