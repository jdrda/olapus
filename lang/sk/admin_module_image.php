<?php

return [
    'name' => 'Obrázky',
    'description' => 'Správa obrázkov',
    'delete_row_confirmation' => 'Naozaj chcete odstrániť obrázok s názvom',
    'fields' => [
        'id' => 'ID',
        'name' => 'Názov',
        'description' => 'Popis',
        'alt' => 'Alternatívny text (atribút ALT)',
        'url' => 'URL',
        'image' => 'Súbor s obrázkom',
        'category' => 'Kategória',
        'created_at' => 'Vytvorené',
        'updated_at' => 'Aktualizované',
        'deleted_at' => 'Odstránené',
    ],
    'action' => 'Akcia',
    'get_url' => 'Získať URL',
    'get_info' => 'Podrobnosti',
    'detail_modal' => [
        'width' => 'Šírka',
        'height' => 'Výška',
        'px' => 'px',
        'size' => 'Veľkosť',
        'type' => 'Typ',
        'original_name' => 'Pôvodný názov',
    ],
    'url_modal' => [
        'url_of' => 'URL adresa',
        'instruction' => 'Stlačením CTRL + C skopírujete URL',
        'subinstruction' => '(potom stlačte ESCAPE alebo kliknite na x v pravom hornom rohu a okno sa zavrie)',
    ],
];
