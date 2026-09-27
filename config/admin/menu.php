<?php
/**
 * Left menu configuration
 * 
 * Configuration array for admin left menu
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
     * Item Dashboard
     */
    'dashboard' => [
        'name'  => 'admin_module_dashboard.name',
        'class' => 'menu-class-dashboard',
        'route' => 'admin.dashboard.index',
        'icon'  => 'bi bi-speedometer2',
        'items' => [
        ]
    ],
    
    /**
     * Group Publishing
     */
    'publishing' => [
        'name'  => 'admin.menu_group_publishing',
        'class' => 'menu-class-publishing',
        'route' => null,
        'icon'  => 'bi bi-newspaper',
        'items' => [
            
            /**
             * Item Page
             */
            'page' => [
                'name'  => 'admin_module_page.name',
                'class' => 'menu-class-page',
                'route' => 'admin.page.index',
                'icon'  => 'bi bi-file-earmark-text',
            ],
            
            /**
             * Item Page category
             */
            'pageCategory' => [
                'name'  => 'admin_module_pagecategory.name',
                'class' => 'menu-class-pagecategory',
                'route' => 'admin.pageCategory.index',
                'icon'  => 'bi bi-collection',
            ],
            
            /**
             * Item Article
             */
            'article' => [
                'name'  => 'admin_module_article.name',
                'class' => 'menu-class-article',
                'route' => 'admin.article.index',
                'icon'  => 'bi bi-newspaper',
            ],
            
            /**
             * Item Article category
             */
            'articleCategory' => [
                'name'  => 'admin_module_articlecategory.name',
                'class' => 'menu-class-articlecategory',
                'route' => 'admin.articleCategory.index',
                'icon'  => 'bi bi-files',
            ],
            
            /**
             * Feedback
             */
            'feedback' => [
                'name'  => 'admin_module_feedback.name',
                'class' => 'menu-class-feedback',
                'route' => 'admin.feedback.index',
                'icon'  => 'bi bi-chat-left-text',
            ],
            
            /**
             * Comments
             */
            'comment' => [
                'name'  => 'admin_module_comment.name',
                'class' => 'menu-class-comment',
                'route' => 'admin.comment.index',
                'icon'  => 'bi bi-chat-dots',
            ],
        ],
    ],
    /**
     * /Group Publishing
     */
    
    /**
     * Group Components
     */
    'components' => [
        'name'  => 'admin.menu_group_components',
        'class' => 'menu-class-components',
        'route' => null,
        'icon'  => 'bi bi-boxes',
        'items' => [
            
            /**
             * Item Slider
             */
            'slider' => [
                'name'  => 'admin_module_slider.name',
                'class' => 'menu-class-slider',
                'route' => 'admin.slider.index',
                'icon'  => 'bi bi-sliders',
            ],
            
            /**
             * Item Slide
             */
            'slide' => [
                'name'  => 'admin_module_slide.name',
                'class' => 'menu-class-slide',
                'route' => 'admin.slide.index',
                'icon'  => 'bi bi-easel',
            ],
            
        ],
    ],
    /**
     * /Group Components
     */
    
    
    /**
     * Group Advertising
     */
    'advertising' => [
        'name'  => 'admin.menu_group_advertising',
        'class' => 'menu-class-advertising',
        'route' => null,
        'icon'  => 'bi bi-megaphone',
        'items' => [
            
            /**
             * Item Advert
             */
            'advert' => [
                'name'  => 'admin_module_advert.name',
                'class' => 'menu-class-advert',
                'route' => 'admin.advert.index',
                'icon'  => 'bi bi-megaphone',
            ],
            
            /**
             * Item Advert location
             */
            'advertLocation' => [
                'name'  => 'admin_module_advertlocation.name',
                'class' => 'menu-class-advertlocation',
                'route' => 'admin.advertLocation.index',
                'icon'  => 'bi bi-building',
            ],
            
        ],
    ],
    /**
     * /Group Advertising
     */
    
    /**
     * Group E-commerce
     */
    /*'ecommerce' => [
        'name'  => 'admin.menu_group_ecommerce',
        'class' => 'menu-class-ecommerce',
        'route' => null,
        'icon'  => 'bi bi-cart3',
        'items' => [*/
            
            /**
             * Product categories
             */
            /*'productCategory' => [
                'name'  => 'admin_module_productcategory.name',
                'class' => 'menu-class-productcategory',
                'route' => 'admin.productCategory.index',
                'icon'  => 'bi bi-boxes',
            ],*/
            
            /**
             * Products
             */
            /*'product' => [
                'name'  => 'admin_module_product.name',
                'class' => 'menu-class-product',
                'route' => 'admin.product.index',
                'icon'  => 'bi bi-box',
            ],
            
        ],
    ],*/
    /**
     * /Group E-commerce
     */

    /**
     * Group Composite relation example
     */
    'compositeExample' => [
        'name'  => 'admin_composite.menu',
        'class' => 'menu-class-composite-example',
        'route' => null,
        'icon'  => 'bi bi-link-45deg',
        'items' => [
            
            /**
             * Item External record
             */
            'externalRecord' => [
                'name'  => 'admin_module_externalrecord.name',
                'class' => 'menu-class-externalrecord',
                'route' => 'admin.externalRecord.index',
                'icon'  => 'bi bi-folder2-open',
            ],
            
            /**
             * Item External row
             */
            'externalRow' => [
                'name'  => 'admin_module_externalrow.name',
                'class' => 'menu-class-externalrow',
                'route' => 'admin.externalRow.index',
                'icon'  => 'bi bi-list-ul',
            ],
            
        ],
    ],
    /**
     * /Group Composite relation example
     */

    
    /**
     * Generated modules
     *
     * This file is managed by make:admin-module. Generated items can be
     * copied to the appropriate group below or above and then removed from it.
     */
    ...(require __DIR__.'/generated_menu.php'),

    /**
     * Group Administration
     */
    'administration' => [
        'name'  => 'admin.menu_group_administration',
        'class' => 'menu-class-administration',
        'route' => null,
        'icon'  => 'bi bi-gear-wide-connected',
        'items' => [
            
            /**
             * Settings
             */
            'settings' => [
                'name'  => 'admin_module_settings.name',
                'class' => 'menu-class-settings',
                'route' => 'admin.settings.index',
                'icon'  => 'bi bi-gear',
            ],
            
            /**
             * Item User
             */
            'user' => [
                'name'  => 'admin_module_user.name',
                'class' => 'menu-class-user',
                'route' => 'admin.user.index',
                'icon'  => 'bi bi-person',
            ],
            
            /**
             * Item User group
             */
            'usergroup' => [
                'name'  => 'admin_module_usergroup.name',
                'class' => 'menu-class-usergroup',
                'route' => 'admin.usergroup.index',
                'icon'  => 'bi bi-people',
            ],
            
        ],
    ],
    /**
     * /Group Administration
     */

];
