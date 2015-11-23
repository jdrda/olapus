<?php

/*
 * Left menu configuration
 */

return [

    /**
     * Item Dashboard
     */
    'dashboard' => [
        'name'  => 'admin_module_dashboard.name',
        'class' => 'menu-class-dashboard',
        'route' => 'admin.dashboard.index',
        'icon'  => 'fa fa-dashboard',
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
        'icon'  => 'fa fa-dashboard',
        'items' => [
            
            /**
             * Item Page
             */
            'publishing' => [
                'name'  => 'admin_module_page.name',
                'class' => 'menu-class-page',
                'route' => 'admin.page.index',
                'icon'  => 'fa fa-sticky-note-o',
            ],
            
            /**
             * Item Page category
             */
            'pageCategory' => [
                'name'  => 'admin_module_pagecategory.name',
                'class' => 'menu-class-pagecategory',
                'route' => 'admin.pageCategory.index',
                'icon'  => 'fa fa-object-group',
            ],
            
            /**
             * Item Article
             */
            'article' => [
                'name'  => 'admin_module_article.name',
                'class' => 'menu-class-article',
                'route' => 'admin.article.index',
                'icon'  => 'fa fa-newspaper-o',
            ],
            
            /**
             * Item Article category
             */
            'articleCategory' => [
                'name'  => 'admin_module_articlecategory.name',
                'class' => 'menu-class-articlecategory',
                'route' => 'admin.articleCategory.index',
                'icon'  => 'fa fa-clone',
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
        'icon'  => 'fa fa-cubes',
        'items' => [
            
            /**
             * Item Slider
             */
            'slider' => [
                'name'  => 'admin_module_slider.name',
                'class' => 'menu-class-slider',
                'route' => 'admin.slider.index',
                'icon'  => 'fa fa-sliders',
            ],
            
            /**
             * Item Slide
             */
            'slide' => [
                'name'  => 'admin_module_slide.name',
                'class' => 'menu-class-slide',
                'route' => 'admin.slide.index',
                'icon'  => 'fa fa-slideshare',
            ],
            
            
        ],
    ],
    /**
     * /Group Publishing
     */
    
    /**
     * Group Media
     */
    'media' => [
        'name'  => 'admin.menu_group_media',
        'class' => 'menu-class-media',
        'route' => null,
        'icon'  => 'fa fa-camera-retro',
        'items' => [
            
            /**
             * Item Image
             */
            'image' => [
                'name'  => 'admin_module_image.name',
                'class' => 'menu-class-image',
                'route' => 'admin.image.index',
                'icon'  => 'fa fa-image',
            ],
            
            /**
             * Item Image category
             */
            'imageCategory' => [
                'name'  => 'admin_module_imageCategory.name',
                'class' => 'menu-class-imagecategory',
                'route' => 'admin.imageCategory.index',
                'icon'  => 'fa fa-file-image-o',
            ],
            
            
        ],
    ],
    /**
     * /Group Media
     */
    
    /**
     * Group Administration
     */
    'administration' => [
        'name'  => 'admin.menu_group_administration',
        'class' => 'menu-class-administration',
        'route' => null,
        'icon'  => 'fa fa-gears',
        'items' => [
            
            /**
             * Settings Image
             */
            'settings' => [
                'name'  => 'admin_module_settings.name',
                'class' => 'menu-class-settings',
                'route' => 'admin.settings.index',
                'icon'  => 'fa fa-gear',
            ],
            
            /**
             * Item Image category
             */
            'user' => [
                'name'  => 'admin_module_user.name',
                'class' => 'menu-class-user',
                'route' => 'admin.user.index',
                'icon'  => 'fa fa-user',
            ],
            
            
        ],
    ],
    /**
     * /Group Administration
     */
    
];

