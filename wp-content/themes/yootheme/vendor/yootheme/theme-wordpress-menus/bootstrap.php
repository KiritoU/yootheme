<?php

use YOOtheme\Theme\Wordpress\MenuListener;

return [
    'actions' => [
        'init' => [
            MenuListener::class => 'registerMenus',
        ],
    ],
    'filters' => [
        'wp_nav_menu_args' => [
            MenuListener::class => 'filterMenuArgs',
        ],

        'widget_nav_menu_args' => [
            MenuListener::class => ['filterWidgetArgs', 10, 4],
        ],

        'wp_get_nav_menus' => [
            MenuListener::class => ['getNavMenus', 10, 2],
        ],

        'wp_nav_menu_objects' => [
            MenuListener::class => ['getNavMenuObjects', 10, 2],
        ],
    ],
];
