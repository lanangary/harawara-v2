<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Badrock_Elementor_Slider_Widget') && class_exists('Elementor\Widget_Base')) {
    class Badrock_Elementor_Slider_Widget extends \Elementor\Widget_Base
    {
        public function get_name()
        {
            return 'badrock_slider_block';
        }
        public function get_title()
        {
            return __('Badrock Slider Block', 'badrock-elementor');
        }
        public function get_icon()
        {
            return 'eicon-slides';
        }
        public function get_categories()
        {
            return ['general'];
        }
        protected function _register_controls()
        {
            $this->start_controls_section(
                'slider_content_section',
                [
                    'label' => __('Slider Content', 'badrock-elementor'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
            $this->add_control(
                'use_acf_gallery',
                [
                    'label' => __('Use ACF Gallery Field?', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => '',
                    'label_on' => __('Yes', 'badrock-elementor'),
                    'label_off' => __('No', 'badrock-elementor'),
                    'return_value' => 'yes',
                ]
            );
            $this->add_control(
                'acf_gallery_field',
                [
                    'label' => __('ACF Gallery Field Name', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'condition' => [
                        'use_acf_gallery' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'acf_field_type',
                [
                    'label' => __('ACF Field Type', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'gallery' => __('Gallery', 'badrock-elementor'),
                        'image' => __('Image', 'badrock-elementor'),
                    ],
                    'default' => 'gallery',
                    'condition' => [
                        'use_acf_gallery' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'slider_images',
                [
                    'label' => __('Slider Images', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::GALLERY,
                    'default' => [],
                    'condition' => [
                        'use_acf_gallery!' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'hide_arrows',
                [
                    'label' => __('Hide Arrow Navigation', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'badrock-elementor'),
                    'label_off' => __('No', 'badrock-elementor'),
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );
            $this->add_control(
                'hide_dots',
                [
                    'label' => __('Hide Dots (Pagination)', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'badrock-elementor'),
                    'label_off' => __('No', 'badrock-elementor'),
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );
            $this->add_control(
                'hide_scrollbar',
                [
                    'label' => __('Hide Scrollbar', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'badrock-elementor'),
                    'label_off' => __('No', 'badrock-elementor'),
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );
            $this->end_controls_section();
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();
            $images = [];
            $use_acf = isset($settings['use_acf_gallery']) && $settings['use_acf_gallery'] === 'yes' && !empty($settings['acf_gallery_field']) && function_exists('get_field');
            if ($use_acf) {
                global $post;
                $acf_field_type = isset($settings['acf_field_type']) ? $settings['acf_field_type'] : 'gallery';
                $acf_value = get_field($settings['acf_gallery_field'], $post ? $post->ID : null);
                if ($acf_field_type === 'gallery' && is_array($acf_value) && count($acf_value) > 0) {
                    foreach ($acf_value as $acf_image) {
                        if (is_array($acf_image) && isset($acf_image['url'])) {
                            $images[] = $acf_image;
                        } elseif (is_numeric($acf_image)) {
                            $img_url = wp_get_attachment_url($acf_image);
                            if ($img_url) {
                                $images[] = ['url' => $img_url];
                            }
                        }
                    }
                } elseif ($acf_field_type === 'image' && !empty($acf_value)) {
                    if (is_array($acf_value) && isset($acf_value['url'])) {
                        $images[] = $acf_value;
                    } elseif (is_numeric($acf_value)) {
                        $img_url = wp_get_attachment_url($acf_value);
                        if ($img_url) {
                            $images[] = ['url' => $img_url];
                        }
                    }
                }
            }
            // Fallback: if no images from ACF, use backend images
            if (empty($images) && !empty($settings['slider_images'])) {
                $images = $settings['slider_images'];
            }
            if (! empty($images)) {
                $hide_arrows_class = $settings['hide_arrows'] === 'yes' ? ' hide-arrows' : '';
                $hide_dots_class = $settings['hide_dots'] === 'yes' ? ' hide-dots' : '';
                $hide_scrollbar_class = $settings['hide_scrollbar'] === 'yes' ? ' hide-scrollbar' : '';
                echo '<div class="swiper badrock-slider-block' . esc_attr($hide_arrows_class . $hide_dots_class . $hide_scrollbar_class) . '">';
                echo '<div class="swiper-wrapper">';
                foreach ($images as $image) {
                    echo '<div class="swiper-slide"><img src="' . esc_url($image['url']) . '" alt=""></div>';
                }
                echo '</div>';
                if ($settings['hide_dots'] !== 'yes') {
                    echo '<div class="swiper-pagination"></div>';
                }
                if ($settings['hide_arrows'] !== 'yes') {
                    echo '<div class="swiper-button-prev"></div>';
                    echo '<div class="swiper-button-next"></div>';
                }
                if ($settings['hide_scrollbar'] !== 'yes') {
                    echo '<div class="swiper-scrollbar"></div>';
                }
                echo '</div>';
            }
        }
    }
}
