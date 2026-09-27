<?php

return [
    'name' => 'Usuarios',
    'description' => 'Gestionar usuarios',
    'delete_row_confirmation' => '¿Seguro que desea eliminar el usuario',
    'fields' => [
        'id' => 'ID',
        'name' => 'Nombre',
        'email' => 'Correo electrónico',
        'password' => 'Contraseña',
        'password_again' => 'Repetir contraseña',
        'roles' => 'Grupos de usuarios',
        'created_at' => 'Creado',
        'updated_at' => 'Actualizado',
        'deleted_at' => 'Eliminado',
    ],
];
