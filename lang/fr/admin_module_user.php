<?php

return [
    'name' => 'Utilisateurs',
    'description' => 'Gérer les utilisateurs',
    'delete_row_confirmation' => 'Voulez-vous vraiment supprimer l’utilisateur',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nom',
        'email' => 'E-mail',
        'password' => 'Mot de passe',
        'password_again' => 'Répéter le mot de passe',
        'roles' => 'Groupes d’utilisateurs',
        'created_at' => 'Créé',
        'updated_at' => 'Mis à jour',
        'deleted_at' => 'Supprimé',
    ],
];
