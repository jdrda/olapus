<?php

return [
    'name' => 'Benutzergruppen',
    'description' => 'Benutzergruppen verwalten',
    'delete_row_confirmation' => 'Möchten Sie die Benutzergruppe wirklich löschen?',
    'fields' => [
        'id' => 'ID',
        'name' => 'Name',
        'name_help' => 'Interne Spatie-Rollenkennung (z. B. admin, editor).',
        'description' => 'Beschreibung',
        'users' => 'Benutzer',
        'updated_at' => 'Aktualisiert',
    ],
];
