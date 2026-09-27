<?php

return [
    'name' => 'Obrazy',
    'description' => 'Zarządzanie obrazami',
    'delete_row_confirmation' => 'Czy na pewno chcesz usunąć obraz o nazwie',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nazwa',
        'description' => 'Opis',
        'alt' => 'Tekst alternatywny (atrybut ALT)',
        'url' => 'URL',
        'image' => 'Plik obrazu',
        'category' => 'Kategoria',
        'created_at' => 'Utworzono',
        'updated_at' => 'Zaktualizowano',
        'deleted_at' => 'Usunięto',
    ],
    'action' => 'Akcja',
    'get_url' => 'Pobierz URL',
    'get_info' => 'Szczegóły',
    'detail_modal' => [
        'width' => 'Szerokość',
        'height' => 'Wysokość',
        'px' => 'px',
        'size' => 'Rozmiar',
        'type' => 'Typ',
        'original_name' => 'Oryginalna nazwa',
    ],
    'url_modal' => [
        'url_of' => 'Adres URL',
        'instruction' => 'Naciśnij CTRL + C, aby skopiować URL',
        'subinstruction' => '(następnie naciśnij ESCAPE lub kliknij x w prawym górnym rogu, aby zamknąć to okno)',
    ],
];
