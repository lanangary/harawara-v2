<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Register both widgets using Elementor's modern API
add_action('elementor/widgets/register', function ($widgets_manager) {
    if (class_exists('Badrock_Elementor_Slider_Widget')) {
        $widgets_manager->register(new Badrock_Elementor_Slider_Widget());
    }
    if (class_exists('Badrock_Elementor_Opening_Widget')) {
        $widgets_manager->register(new Badrock_Elementor_Opening_Widget());
    }
});

// Enqueue JS and CSS for Slider widget
add_action('wp_enqueue_scripts', function () {
    // Slider widget assets
    $slider_url = plugin_dir_url(__FILE__) . '/../widget/slider/';
    $slider_path = __DIR__ . '/../widget/slider/';
    wp_enqueue_script(
        'badrock-slider',
        $slider_url . 'widget.js',
        ['jquery'],
        filemtime($slider_path . 'widget.js'),
        true
    );
    wp_enqueue_style(
        'badrock-slider',
        $slider_url . 'widget.css',
        [],
        filemtime($slider_path . 'widget.css')
    );
    
    // Opening widget assets
    $opening_url = plugin_dir_url(__FILE__) . '/../widget/opening/';
    $opening_path = __DIR__ . '/../widget/opening/';
    wp_enqueue_script(
        'badrock-opening',
        $opening_url . 'widget.js',
        ['jquery'],
        filemtime($opening_path . 'widget.js'),
        true
    );
    wp_enqueue_style(
        'badrock-opening',
        $opening_url . 'widget.css',
        [],
        filemtime($opening_path . 'widget.css')
    );
});
