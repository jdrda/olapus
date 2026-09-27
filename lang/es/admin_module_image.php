<?php

return [
    'name' => 'Imágenes',
    'description' => 'Gestionar imágenes',
    'delete_row_confirmation' => '¿Seguro que desea eliminar la imagen llamada',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nombre',
        'description' => 'Descripción',
        'alt' => 'Texto alternativo (atributo ALT)',
        'url' => 'URL',
        'image' => 'Archivo de imagen',
        'category' => 'Categoría',
        'created_at' => 'Creado',
        'updated_at' => 'Actualizado',
        'deleted_at' => 'Eliminado',
    ],
    'action' => 'Acción',
    'get_url' => 'Obtener URL',
    'get_info' => 'Detalles',
    'detail_modal' => [
        'width' => 'Anchura',
        'height' => 'Altura',
        'px' => 'px',
        'size' => 'Tamaño',
        'type' => 'Tipo',
        'original_name' => 'Nombre original',
    ],
    'url_modal' => [
        'url_of' => 'Dirección URL',
        'instruction' => 'Pulse CTRL + C para copiar la URL',
        'subinstruction' => '(después pulse ESCAPE o haga clic en la x de la esquina superior derecha para cerrar esta ventana)',
    ],
];
