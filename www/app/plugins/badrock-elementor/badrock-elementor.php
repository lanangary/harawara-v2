<?php

/**
 * Plugin Name: Badrock Elementor
 * Description: Adds a custom block to Elementor.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: badrock-elementor
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

// Check if Elementor is active.
function badrock_elementor_check_dependencies()
{
    if (! did_action('elementor/loaded')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>' . esc_html__('Elementor must be installed and activated for Badrock Elementor to work.', 'badrock-elementor') . '</p></div>';
        });
        return;
    }
}
add_action('plugins_loaded', 'badrock_elementor_check_dependencies');

// Include the custom widget class.
function badrock_elementor_load_widget()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-badrock-elementor-widget.php';
    require_once plugin_dir_path(__FILE__) . 'widget/slider/widget.php'; // Ensure slider widget is included
}
add_action('elementor/widgets/widgets_registered', 'badrock_elementor_load_widget');

// Register custom ACF image dynamic tag for Elementor
// Register custom ACF image dynamic tag for Elementor (now handled in badrock-init.php)
require_once __DIR__ . '/badrock-init.php';

// Add custom ACF field selector to the Container widget's Layout > Additional Settings
add_action('elementor/element/container/section_additional_settings/after_section_end', function ($element, $args) {
    // Only add to the Container widget
    /** @var \Elementor\Element_Base $element */
    $element->start_controls_section(
        'badrock_acf_section',
        [
            'label' => __('Badrock ACF', 'badrock-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
        ]
    );
    $element->add_control(
        'badrock_acf_field',
        [
            'label' => __('ACF Field Name', 'badrock-elementor'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'description' => __('Enter the ACF field name to use in this container.', 'badrock-elementor'),
        ]
    );
    $element->end_controls_section();
}, 10, 2);
