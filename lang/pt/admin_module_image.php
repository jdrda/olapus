<?php

return [
    'name' => 'Imagens',
    'description' => 'Gerir imagens',
    'delete_row_confirmation' => 'Tem a certeza de que pretende eliminar a imagem chamada',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nome',
        'description' => 'Descrição',
        'alt' => 'Texto alternativo (atributo ALT)',
        'url' => 'URL',
        'image' => 'Ficheiro de imagem',
        'category' => 'Categoria',
        'created_at' => 'Criado',
        'updated_at' => 'Atualizado',
        'deleted_at' => 'Eliminado',
    ],
    'action' => 'Ação',
    'get_url' => 'Obter URL',
    'get_info' => 'Detalhes',
    'detail_modal' => [
        'width' => 'Largura',
        'height' => 'Altura',
        'px' => 'px',
        'size' => 'Tamanho',
        'type' => 'Tipo',
        'original_name' => 'Nome original',
    ],
    'url_modal' => [
        'url_of' => 'Endereço URL',
        'instruction' => 'Prima CTRL + C para copiar o URL',
        'subinstruction' => '(depois prima ESCAPE ou clique no x no canto superior direito para fechar esta janela)',
    ],
];
