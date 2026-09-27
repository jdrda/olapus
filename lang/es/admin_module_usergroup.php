<?php

return [
    'name' => 'Grupos de usuarios',
    'description' => 'Administrar grupos de usuarios',
    'delete_row_confirmation' => '¿Seguro que desea eliminar el grupo de usuarios?',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nombre',
        'name_help' => 'Identificador interno del rol de Spatie (por ejemplo admin, editor).',
        'description' => 'Descripción',
        'users' => 'Usuarios',
        'updated_at' => 'Actualizado',
    ],
];
