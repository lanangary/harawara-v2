<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Badrock_Elementor_Opening_Widget') && class_exists('Elementor\Widget_Base')) {
    class Badrock_Elementor_Opening_Widget extends \Elementor\Widget_Base
    {
        public function get_name()
        {
            return 'badrock_opening';
        }
        
        public function get_title()
        {
            return __('Badrock Opening', 'badrock-elementor');
        }
        
        public function get_icon()
        {
            return 'eicon-lightbox-expand';
        }
        
        public function get_categories()
        {
            return ['general'];
        }
        
        protected function _register_controls()
        {
            // Content Section
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Content', 'badrock-elementor'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'background_image',
                [
                    'label' => __('Background Image', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $this->add_control(
                'background_size',
                [
                    'label' => __('Background Size', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'cover',
                    'options' => [
                        'auto' => __('Auto', 'badrock-elementor'),
                        'cover' => __('Cover', 'badrock-elementor'),
                        'contain' => __('Contain', 'badrock-elementor'),
                        '100% 100%' => __('Stretch', 'badrock-elementor'),
                        'custom' => __('Custom', 'badrock-elementor'),
                    ],
                    'condition' => [
                        'background_image[url]!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'background_size_custom',
                [
                    'label' => __('Custom Size', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vw', 'vh'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 2000,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                        'vw' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                        'vh' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],
                    'default' => [
                        'size' => 100,
                        'unit' => '%',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-content' => 'background-size: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'background_image[url]!' => '',
                        'background_size' => 'custom',
                    ],
                ]
            );

            $this->add_control(
                'background_position',
                [
                    'label' => __('Background Position', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'center center',
                    'options' => [
                        'left top' => __('Left Top', 'badrock-elementor'),
                        'left center' => __('Left Center', 'badrock-elementor'),
                        'left bottom' => __('Left Bottom', 'badrock-elementor'),
                        'center top' => __('Center Top', 'badrock-elementor'),
                        'center center' => __('Center Center', 'badrock-elementor'),
                        'center bottom' => __('Center Bottom', 'badrock-elementor'),
                        'right top' => __('Right Top', 'badrock-elementor'),
                        'right center' => __('Right Center', 'badrock-elementor'),
                        'right bottom' => __('Right Bottom', 'badrock-elementor'),
                        'custom' => __('Custom', 'badrock-elementor'),
                    ],
                    'condition' => [
                        'background_image[url]!' => '',
                    ],
                ]
            );

            $this->add_responsive_control(
                'background_position_x',
                [
                    'label' => __('X Position', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'size' => 50,
                        'unit' => '%',
                    ],
                    'condition' => [
                        'background_image[url]!' => '',
                        'background_position' => 'custom',
                    ],
                ]
            );

            $this->add_responsive_control(
                'background_position_y',
                [
                    'label' => __('Y Position', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%'],
                    'range' => [
                        'px' => [
                            'min' => -500,
                            'max' => 500,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'size' => 50,
                        'unit' => '%',
                    ],
                    'condition' => [
                        'background_image[url]!' => '',
                        'background_position' => 'custom',
                    ],
                ]
            );

            $this->add_control(
                'background_repeat',
                [
                    'label' => __('Background Repeat', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'no-repeat',
                    'options' => [
                        'no-repeat' => __('No Repeat', 'badrock-elementor'),
                        'repeat' => __('Repeat', 'badrock-elementor'),
                        'repeat-x' => __('Repeat Horizontally', 'badrock-elementor'),
                        'repeat-y' => __('Repeat Vertically', 'badrock-elementor'),
                    ],
                    'condition' => [
                        'background_image[url]!' => '',
                    ],
                ]
            );

            $this->add_control(
                'background_attachment',
                [
                    'label' => __('Background Attachment', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'scroll',
                    'options' => [
                        'scroll' => __('Scroll', 'badrock-elementor'),
                        'fixed' => __('Fixed', 'badrock-elementor'),
                        'local' => __('Local', 'badrock-elementor'),
                    ],
                    'condition' => [
                        'background_image[url]!' => '',
                    ],
                ]
            );

            $this->add_control(
                'dimensions_heading',
                [
                    'label' => __('Dimensions', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'content_width',
                [
                    'label' => __('Width', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vw'],
                    'range' => [
                        'px' => [
                            'min' => 300,
                            'max' => 2000,
                        ],
                        '%' => [
                            'min' => 50,
                            'max' => 100,
                        ],
                        'vw' => [
                            'min' => 50,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'size' => 100,
                        'unit' => 'vw',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-content' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'content_height',
                [
                    'label' => __('Height', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px', '%', 'vh'],
                    'range' => [
                        'px' => [
                            'min' => 400,
                            'max' => 2000,
                        ],
                        '%' => [
                            'min' => 50,
                            'max' => 100,
                        ],
                        'vh' => [
                            'min' => 50,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'size' => 100,
                        'unit' => 'vh',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-content' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'enable_border_radius',
                [
                    'label' => __('Enable Border Radius', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'badrock-elementor'),
                    'label_off' => __('No', 'badrock-elementor'),
                    'return_value' => 'yes',
                    'default' => '',
                ]
            );

            $this->add_responsive_control(
                'content_border_radius',
                [
                    'label' => __('Border Radius', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'default' => [
                        'top' => 10,
                        'right' => 10,
                        'bottom' => 10,
                        'left' => 10,
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'condition' => [
                        'enable_border_radius' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'bride_name',
                [
                    'label' => __('Bride Name', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Bride', 'badrock-elementor'),
                    'placeholder' => __('Enter bride name', 'badrock-elementor'),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $this->add_control(
                'groom_name',
                [
                    'label' => __('Groom Name', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Groom', 'badrock-elementor'),
                    'placeholder' => __('Enter groom name', 'badrock-elementor'),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $this->add_control(
                'wedding_date',
                [
                    'label' => __('Wedding Date', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('12.12.2024', 'badrock-elementor'),
                    'placeholder' => __('Enter wedding date', 'badrock-elementor'),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $this->add_control(
                'invitation_text',
                [
                    'label' => __('Invitation Text', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'default' => __('Wedding Invitation', 'badrock-elementor'),
                    'placeholder' => __('Enter invitation text', 'badrock-elementor'),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $this->add_control(
                'button_text',
                [
                    'label' => __('Button Text', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Open Invitation', 'badrock-elementor'),
                    'placeholder' => __('Enter button text', 'badrock-elementor'),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $this->end_controls_section();

            // Animation Settings
            $this->start_controls_section(
                'animation_section',
                [
                    'label' => __('Animation Settings', 'badrock-elementor'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'animation_type',
                [
                    'label' => __('Animation Type', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'fade',
                    'options' => [
                        'fade' => __('Fade', 'badrock-elementor'),
                        'slide-up' => __('Slide Up', 'badrock-elementor'),
                        'slide-down' => __('Slide Down', 'badrock-elementor'),
                        'slide-left' => __('Slide Left', 'badrock-elementor'),
                        'slide-right' => __('Slide Right', 'badrock-elementor'),
                        'zoom-out' => __('Zoom Out', 'badrock-elementor'),
                    ],
                ]
            );

            $this->add_control(
                'animation_duration',
                [
                    'label' => __('Animation Duration (ms)', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 800,
                    'min' => 100,
                    'max' => 3000,
                    'step' => 100,
                ]
            );

            $this->end_controls_section();

            // Style Section - Overlay
            $this->start_controls_section(
                'overlay_style_section',
                [
                    'label' => __('Overlay Style', 'badrock-elementor'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'overlay_background',
                [
                    'label' => __('Overlay Background', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => 'rgba(0,0,0,0.8)',
                ]
            );

            $this->add_responsive_control(
                'overlay_padding',
                [
                    'label' => __('Padding', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();

            // Style Section - Typography
            $this->start_controls_section(
                'typography_style_section',
                [
                    'label' => __('Typography', 'badrock-elementor'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'bride_groom_typography',
                    'label' => __('Names Typography', 'badrock-elementor'),
                    'selector' => '{{WRAPPER}} .badrock-opening-names',
                ]
            );

            $this->add_control(
                'names_color',
                [
                    'label' => __('Names Color', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-names' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'date_typography',
                    'label' => __('Date Typography', 'badrock-elementor'),
                    'selector' => '{{WRAPPER}} .badrock-opening-date',
                ]
            );

            $this->add_control(
                'date_color',
                [
                    'label' => __('Date Color', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-date' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'text_typography',
                    'label' => __('Invitation Text Typography', 'badrock-elementor'),
                    'selector' => '{{WRAPPER}} .badrock-opening-text',
                ]
            );

            $this->add_control(
                'text_color',
                [
                    'label' => __('Invitation Text Color', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-text' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_section();

            // Style Section - Button
            $this->start_controls_section(
                'button_style_section',
                [
                    'label' => __('Button Style', 'badrock-elementor'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typography',
                    'label' => __('Button Typography', 'badrock-elementor'),
                    'selector' => '{{WRAPPER}} .badrock-opening-button',
                ]
            );

            $this->add_control(
                'button_background_color',
                [
                    'label' => __('Background Color', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#d4af37',
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-button' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_text_color',
                [
                    'label' => __('Text Color', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-button' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_hover_background_color',
                [
                    'label' => __('Hover Background Color', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#b8941f',
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-button:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'button_padding',
                [
                    'label' => __('Padding', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'default' => [
                        'top' => 15,
                        'right' => 30,
                        'bottom' => 15,
                        'left' => 30,
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'button_border_radius',
                [
                    'label' => __('Border Radius', 'badrock-elementor'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'default' => [
                        'top' => 5,
                        'right' => 5,
                        'bottom' => 5,
                        'left' => 5,
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .badrock-opening-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->end_controls_section();
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();
            $animation_type = $settings['animation_type'];
            $animation_duration = $settings['animation_duration'];
            
            // Build background styles
            $background_styles = [];
            
            if (!empty($settings['background_image']['url'])) {
                $background_styles[] = 'background-image: url(' . esc_url($settings['background_image']['url']) . ')';
                
                // Background size
                if (!empty($settings['background_size']) && $settings['background_size'] !== 'custom') {
                    $background_styles[] = 'background-size: ' . $settings['background_size'];
                }
                
                // Background position
                if (!empty($settings['background_position']) && $settings['background_position'] !== 'custom') {
                    $background_styles[] = 'background-position: ' . $settings['background_position'];
                } elseif ($settings['background_position'] === 'custom') {
                    $pos_x = !empty($settings['background_position_x']) ? $settings['background_position_x']['size'] . $settings['background_position_x']['unit'] : '50%';
                    $pos_y = !empty($settings['background_position_y']) ? $settings['background_position_y']['size'] . $settings['background_position_y']['unit'] : '50%';
                    $background_styles[] = 'background-position: ' . $pos_x . ' ' . $pos_y;
                }
                
                // Background repeat
                if (!empty($settings['background_repeat'])) {
                    $background_styles[] = 'background-repeat: ' . $settings['background_repeat'];
                }
                
                // Background attachment
                if (!empty($settings['background_attachment'])) {
                    $background_styles[] = 'background-attachment: ' . $settings['background_attachment'];
                }
            }
            
            $style_attr = !empty($background_styles) ? 'style="' . implode('; ', $background_styles) . ';"' : '';
            ?>
            <div class="badrock-opening-overlay" data-animation="<?php echo esc_attr($animation_type); ?>" data-duration="<?php echo esc_attr($animation_duration); ?>">
                <div class="badrock-opening-content" <?php echo $style_attr; ?>>
                    <div class="badrock-opening-inner">
                        <?php if (!empty($settings['invitation_text'])): ?>
                            <div class="badrock-opening-text"><?php $this->print_unescaped_setting('invitation_text'); ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['bride_name']) || !empty($settings['groom_name'])): ?>
                            <div class="badrock-opening-names">
                                <?php if (!empty($settings['bride_name'])): ?>
                                    <span class="bride-name"><?php $this->print_unescaped_setting('bride_name'); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($settings['bride_name']) && !empty($settings['groom_name'])): ?>
                                    <span class="separator">&</span>
                                <?php endif; ?>
                                <?php if (!empty($settings['groom_name'])): ?>
                                    <span class="groom-name"><?php $this->print_unescaped_setting('groom_name'); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['wedding_date'])): ?>
                            <div class="badrock-opening-date"><?php $this->print_unescaped_setting('wedding_date'); ?></div>
                        <?php endif; ?>
                        
                        <button class="badrock-opening-button" type="button">
                            <?php $this->print_unescaped_setting('button_text'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}