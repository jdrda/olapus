<?php

return [
    'name' => 'Afbeeldingen',
    'description' => 'Afbeeldingen beheren',
    'delete_row_confirmation' => 'Weet u zeker dat u de afbeelding met de naam wilt verwijderen?',
    'fields' => [
        'id' => 'ID',
        'name' => 'Naam',
        'description' => 'Beschrijving',
        'alt' => 'Alternatieve tekst (ALT-attribuut)',
        'url' => 'URL',
        'image' => 'Afbeeldingsbestand',
        'category' => 'Categorie',
        'created_at' => 'Aangemaakt',
        'updated_at' => 'Bijgewerkt',
        'deleted_at' => 'Verwijderd',
    ],
    'action' => 'Actie',
    'get_url' => 'URL ophalen',
    'get_info' => 'Details',
    'detail_modal' => [
        'width' => 'Breedte',
        'height' => 'Hoogte',
        'px' => 'px',
        'size' => 'Grootte',
        'type' => 'Type',
        'original_name' => 'Oorspronkelijke naam',
    ],
    'url_modal' => [
        'url_of' => 'URL',
        'instruction' => 'Druk op CTRL + C om de URL te kopiëren',
        'subinstruction' => '(druk daarna op ESCAPE of klik rechtsboven op de x om dit venster te sluiten)',
    ],
];
