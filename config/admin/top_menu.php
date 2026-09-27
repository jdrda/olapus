<?php
/**
 * Top menu configuration
 *
 * Configuration array for admin top navbar shortcuts.
 * Keep this file project-specific and arrange the shortcuts as needed.
 * The structure intentionally follows config/admin/menu.php.
 *
 * @category Configuration
 * @subpackage Admin
 * @package Olapus
 * @author Jan Drda <jdrda@outlook.com>
 * @copyright Jan Drda
 * @license https://opensource.org/licenses/MIT MIT
 */

return [

    /**
     * Dashboard
     */
    'dashboard' => [
        'name'  => 'admin_module_dashboard.name',
        'class' => 'menu-class-dashboard',
        'route' => 'admin.dashboard.index',
        'icon'  => 'bi bi-speedometer2',
        'items' => [
        ],
    ],

    /**
     * Pages
     */
    'page' => [
        'name'  => 'admin_module_page.name',
        'class' => 'menu-class-page',
        'route' => 'admin.page.index',
        'icon'  => 'bi bi-file-earmark-text',
        'items' => [
        ],
    ],

    /**
     * Articles
     */
    'article' => [
        'name'  => 'admin_module_article.name',
        'class' => 'menu-class-article',
        'route' => 'admin.article.index',
        'icon'  => 'bi bi-newspaper',
        'items' => [
        ],
    ],

    /**
     * Users
     */
    'user' => [
        'name'  => 'admin_module_user.name',
        'class' => 'menu-class-user',
        'route' => 'admin.user.index',
        'icon'  => 'bi bi-person',
        'items' => [
        ],
    ],

];
