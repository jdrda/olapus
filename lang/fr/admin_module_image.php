<?php

return [
    'name' => 'Images',
    'description' => 'Gérer les images',
    'delete_row_confirmation' => 'Voulez-vous vraiment supprimer l’image nommée',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nom',
        'description' => 'Description',
        'alt' => 'Texte alternatif (attribut ALT)',
        'url' => 'URL',
        'image' => 'Fichier image',
        'category' => 'Catégorie',
        'created_at' => 'Créé',
        'updated_at' => 'Mis à jour',
        'deleted_at' => 'Supprimé',
    ],
    'action' => 'Action',
    'get_url' => 'Obtenir l’URL',
    'get_info' => 'Détails',
    'detail_modal' => [
        'width' => 'Largeur',
        'height' => 'Hauteur',
        'px' => 'px',
        'size' => 'Taille',
        'type' => 'Type',
        'original_name' => 'Nom d’origine',
    ],
    'url_modal' => [
        'url_of' => 'Adresse URL',
        'instruction' => 'Appuyez sur CTRL + C pour copier l’URL',
        'subinstruction' => '(puis appuyez sur ÉCHAP ou cliquez sur x en haut à droite pour fermer cette fenêtre)',
    ],
];
