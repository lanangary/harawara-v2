
<?php

// Enqueue CSS for ACF Image Dynamic Tag widget
add_action('wp_enqueue_scripts', function () {

    // Slider widget assets
    $slider_url = plugin_dir_url(__FILE__) . 'widget/slider/';
    $slider_path = __DIR__ . '/widget/slider/';
    // Enqueue Swiper CSS and JS from CDN
    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        '11.0.0'
    );
    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        '11.0.0',
        true
    );
    // Enqueue slider widget assets, depend on Swiper
    wp_enqueue_style(
        'badrock-slider',
        $slider_url . 'widget.css',
        ['swiper'],
        filemtime($slider_path . 'widget.css')
    );
    wp_enqueue_script(
        'badrock-slider',
        $slider_url . 'widget.js',
        ['swiper'],
        filemtime($slider_path . 'widget.js'),
        true
    );
});
