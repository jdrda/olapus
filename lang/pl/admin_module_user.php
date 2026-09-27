<?php

return [
    'name' => 'Użytkownicy',
    'description' => 'Zarządzanie użytkownikami',
    'delete_row_confirmation' => 'Czy na pewno chcesz usunąć użytkownika',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nazwa',
        'email' => 'E-mail',
        'password' => 'Hasło',
        'password_again' => 'Powtórz hasło',
        'password_leave_empty' => 'Podczas edycji pozostaw puste, jeśli nie chcesz zmieniać hasła.',
        'roles' => 'Grupy użytkowników',
        'created_at' => 'Utworzono',
        'updated_at' => 'Zaktualizowano',
        'deleted_at' => 'Usunięto',
    ],
];
