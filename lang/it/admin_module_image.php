<?php

return [
    'name' => 'Immagini',
    'description' => 'Gestisci immagini',
    'delete_row_confirmation' => 'Sei sicuro di voler eliminare l’immagine denominata',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nome',
        'description' => 'Descrizione',
        'alt' => 'Testo alternativo (attributo ALT)',
        'url' => 'URL',
        'image' => 'File immagine',
        'category' => 'Categoria',
        'created_at' => 'Creato',
        'updated_at' => 'Aggiornato',
        'deleted_at' => 'Eliminato',
    ],
    'action' => 'Azione',
    'get_url' => 'Ottieni URL',
    'get_info' => 'Dettagli',
    'detail_modal' => [
        'width' => 'Larghezza',
        'height' => 'Altezza',
        'px' => 'px',
        'size' => 'Dimensione',
        'type' => 'Tipo',
        'original_name' => 'Nome originale',
    ],
    'url_modal' => [
        'url_of' => 'Indirizzo URL',
        'instruction' => 'Premi CTRL + C per copiare l’URL',
        'subinstruction' => '(poi premi ESCAPE o fai clic sulla x in alto a destra per chiudere questa finestra)',
    ],
];
