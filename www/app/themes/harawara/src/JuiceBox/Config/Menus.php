<?php

namespace JuiceBox\Config;

class Menus
{
    public static $menus = array(
        'header_menu' => 'The Header Menu',
        'footer_menu' => 'The Footer Menu',
        'legal_menu'  => 'Legal Pages'
    );

    public static function register($action = 'init', $priority = 10)
    {
        // Register the action
        add_action($action, function () {
            register_nav_menus(static::$menus);
        }, $priority);
    }
}
