<?php
/**
 * Template Name: Subscriber Dashboard Edit
 */

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Get the subscriber's page
$subscriber_page_id = get_user_meta($user_id, 'subscriber_page_id', true);
$subscriber_page = $subscriber_page_id ? get_post($subscriber_page_id) : null;

if (!$subscriber_page) {
    $context = Timber::context();
    $context['edit_message'] = 'Your personal page could not be found.';
    Timber::render(['page--subscriber-dashboard-edit.twig', 'page.twig'], $context);
    exit;
}

// Initialize ACF Field Detection System
use HarawaraTheme\ACF\SubscriberACFFieldDetector;
use HarawaraTheme\ACF\FieldValueProcessor;

$acf_detector = new SubscriberACFFieldDetector();
$acf_processor = new FieldValueProcessor();

// Detect ACF fields for this page
$detected_fields = $acf_detector->detectFields($subscriber_page_id, $user_id);

// Fetch all published Elementor templates of type 'page'
$elementor_templates = get_posts([
    'post_type' => 'elementor_library',
    'post_status' => 'publish',
    'numberposts' => -1,
    'meta_key' => '_elementor_template_type',
    'meta_value' => 'page',
    'orderby' => 'title',
    'order' => 'ASC',
]);


// Get the currently selected template (if any)
$current_template_id = get_user_meta($user_id, 'subscriber_selected_template', true);
if (!$current_template_id) {
    // Try to guess from _elementor_data (first match)
    $current_template_id = '';
}

// Handle frontend form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscriber_page_update'])) {
    if (!wp_verify_nonce($_POST['subscriber_page_nonce'], 'subscriber_page_update_' . $subscriber_page_id)) {
        $message = 'Security check failed. Please try again.';
    } else {
        $new_content = wp_kses_post($_POST['page_content'] ?? '');
        $new_slug = sanitize_title($_POST['page_slug']);
        $selected_template_id = intval($_POST['elementor_template_id'] ?? 0);
        $errors = [];
        
        
        if (empty($new_slug)) {
            $errors[] = 'Slug cannot be empty.';
        } elseif (get_page_by_path($new_slug) && get_page_by_path($new_slug)->ID != $subscriber_page_id) {
            $errors[] = 'This slug is already taken. Please choose another.';
        }
        
        if (empty($errors)) {
            // Update page content and slug first
            wp_update_post([
                'ID' => $subscriber_page_id,
                'post_content' => $new_content,
                'post_name' => $new_slug
            ]);
            
            // Handle template change - Use Elementor's document system (like delete/recreate does)
            if ($selected_template_id) {
                $template_applied = false;
                
                // Method 1: Use Elementor's document system (more like what delete/recreate does)
                if (class_exists('\Elementor\Plugin')) {
                    try {
                        // Get template document
                        $template_document = \Elementor\Plugin::$instance->documents->get($selected_template_id);
                        
                        if ($template_document) {
                            // Get template elements using Elementor's proper method
                            $template_elements = $template_document->get_elements_data();
                            $template_settings = $template_document->get_settings();
                            
                            // Get/create page document
                            $page_document = \Elementor\Plugin::$instance->documents->get($subscriber_page_id);
                            
                            if ($page_document) {
                                // Apply template using Elementor's save method (this is what delete/recreate effectively does)
                                $save_result = $page_document->save([
                                    'elements' => $template_elements,
                                    'settings' => $template_settings ?: []
                                ]);
                                
                                if ($save_result) {
                                    $template_applied = true;
                                }
                            }
                        }
                        
                    } catch (Exception $e) {
                        // Silent fail, will use fallback method
                    }
                }
                
                // Method 2: Fallback to direct copy if document method failed
                if (!$template_applied) {
                    $template_data = get_post_meta($selected_template_id, '_elementor_data', true);
                    
                    if ($template_data) {
                        // Process template data
                        if (is_string($template_data)) {
                            $processed_data = $template_data;
                        } else {
                            $processed_data = wp_json_encode($template_data);
                        }
                        
                        // Apply template data directly
                        update_post_meta($subscriber_page_id, '_elementor_data', $processed_data);
                        update_post_meta($subscriber_page_id, '_elementor_edit_mode', 'builder');
                        update_post_meta($subscriber_page_id, '_elementor_template_type', 'wp-page');
                        update_post_meta($subscriber_page_id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.0.0');
                        
                        $template_applied = true;
                    }
                }
                
                if ($template_applied) {
                    // Ensure proper page template
                    update_post_meta($subscriber_page_id, '_wp_page_template', 'elementor_canvas');
                    
                    // Update user preference
                    update_user_meta($user_id, 'subscriber_selected_template', $selected_template_id);
                    
                    // Clear all Elementor caches (like delete/recreate would)
                    if (class_exists('\Elementor\Plugin')) {
                        // Clear file manager cache
                        \Elementor\Plugin::$instance->files_manager->clear_cache();
                        
                        // Clear posts CSS cache
                        if (method_exists('\Elementor\Plugin::$instance->posts_css_manager', 'clear_cache')) {
                            \Elementor\Plugin::$instance->posts_css_manager->clear_cache();
                        }
                        
                        // Delete CSS meta to force regeneration
                        delete_post_meta($subscriber_page_id, '_elementor_css');
                        
                        // Clear WordPress object cache for this page
                        wp_cache_delete($subscriber_page_id, 'posts');
                        wp_cache_delete($subscriber_page_id, 'post_meta');
                        
                        // Force CSS regeneration
                        if (class_exists('\Elementor\Core\Files\CSS\Post')) {
                            $css_file = new \Elementor\Core\Files\CSS\Post($subscriber_page_id);
                            $css_file->update();
                        }
                    }
                } else {
                    $errors[] = 'Selected template could not be applied. Template data not found.';
                }
            }
            
            // Process and update ACF fields
            if (!empty($_POST) && $detected_fields['field_groups']) {
                $acf_result = $acf_processor->processFormSubmission($_POST, $subscriber_page_id, $user_id);
                
                if (!$acf_result['success']) {
                    $errors = array_merge($errors, $acf_result['errors']);
                } else {
                    // Update ACF fields
                    foreach ($acf_result['data'] as $field_name => $value) {
                        update_field($field_name, $value, $subscriber_page_id);
                    }
                }
            }
            
            if (empty($errors)) {
                if ($selected_template_id) {
                    $message = 'Page template and content updated successfully!';
                } else {
                    $message = 'Page updated successfully!';
                }
            } else {
                $message = implode(' ', $errors);
            }
        } else {
            $message = implode(' ', $errors);
        }
    }
    
    // Refresh the page object after update (page ID might have changed if template was applied)
    $subscriber_page = get_post($subscriber_page_id);
    // Re-detect ACF fields after update
    $detected_fields = $acf_detector->detectFields($subscriber_page_id, $user_id);
}

$context = Timber::context();
$context['edit_message'] = $message;
$context['edit_slug'] = $subscriber_page ? $subscriber_page->post_name : '';
$context['edit_content'] = $subscriber_page ? $subscriber_page->post_content : '';
$context['edit_nonce'] = wp_create_nonce('subscriber_page_update_' . $subscriber_page_id);
$context['subscriber_page_url'] = $subscriber_page ? get_permalink($subscriber_page_id) : '';
$context['subscriber_page_id'] = $subscriber_page_id;
$context['elementor_templates'] = $elementor_templates;
$context['current_template_id'] = $current_template_id;

// Add ACF field data to context
$context['acf_fields'] = $detected_fields;
$context['has_acf_fields'] = !empty($detected_fields['field_groups']);

Timber::render(['page--subscriber-dashboard-edit.twig', 'page.twig'], $context); 