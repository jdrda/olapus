<?php

return [
    'name' => 'Bilder',
    'description' => 'Bilder verwalten',
    'delete_row_confirmation' => 'Möchten Sie das Bild mit dem Namen wirklich löschen?',
    'fields' => [
        'id' => 'ID',
        'name' => 'Name',
        'description' => 'Beschreibung',
        'alt' => 'Alternativtext (ALT-Attribut)',
        'url' => 'URL',
        'image' => 'Bilddatei',
        'category' => 'Kategorie',
        'created_at' => 'Erstellt',
        'updated_at' => 'Aktualisiert',
        'deleted_at' => 'Gelöscht',
    ],
    'action' => 'Aktion',
    'get_url' => 'URL abrufen',
    'get_info' => 'Details',
    'detail_modal' => [
        'width' => 'Breite',
        'height' => 'Höhe',
        'px' => 'px',
        'size' => 'Größe',
        'type' => 'Typ',
        'original_name' => 'Ursprünglicher Name',
    ],
    'url_modal' => [
        'url_of' => 'URL',
        'instruction' => 'Drücken Sie CTRL + C, um die URL zu kopieren',
        'subinstruction' => '(drücken Sie anschließend ESCAPE oder klicken Sie oben rechts auf x, um dieses Fenster zu schließen)',
    ],
];
