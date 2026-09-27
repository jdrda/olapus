<?php

// Managed by make:admin-module. Custom menu groups stay in menu.php.
return [
    'relations_example' => [
        'name' => 'admin_examples.menu',
        'class' => 'menu-class-relations-example',
        'route' => null,
        'icon' => 'bi bi-diagram-3',
        'items' => [
            'project' => [
                'name' => 'admin_module_project.name', 'class' => 'menu-class-project',
                'route' => 'admin.project.index', 'icon' => 'bi bi-kanban',
            ],
            'projectLabel' => [
                'name' => 'admin_module_projectlabel.name', 'class' => 'menu-class-projectlabel',
                'route' => 'admin.projectLabel.index', 'icon' => 'bi bi-tags',
            ],
            'projectTask' => [
                'name' => 'admin_module_projecttask.name', 'class' => 'menu-class-projecttask',
                'route' => 'admin.projectTask.index', 'icon' => 'bi bi-check2-square',
            ],
        ],
    ],
];
