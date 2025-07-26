<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Register both widgets using Elementor's modern API
add_action('elementor/widgets/register', function ($widgets_manager) {
    if (class_exists('Badrock_Elementor_Slider_Widget')) {
        $widgets_manager->register(new Badrock_Elementor_Slider_Widget());
    }
});

// Enqueue JS and CSS for Slider widget
add_action('wp_enqueue_scripts', function () {
    $base_url = plugin_dir_url(__FILE__) . '/../widget/slider/';
    $base_path = __DIR__ . '/../widget/slider/';
    wp_enqueue_script(
        'badrock-slider',
        $base_url . 'slider.js',
        [],
        filemtime($base_path . 'slider.js'),
        true
    );
    wp_enqueue_style(
        'badrock-slider',
        $base_url . 'slider.css',
        [],
        filemtime($base_path . 'slider.css')
    );
});
