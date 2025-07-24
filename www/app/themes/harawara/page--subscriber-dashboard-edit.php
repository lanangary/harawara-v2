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
        $new_content = wp_kses_post($_POST['page_content']);
        $new_slug = sanitize_title($_POST['page_slug']);
        $selected_template_id = intval($_POST['elementor_template_id'] ?? 0);
        $errors = [];
        if (empty($new_slug)) {
            $errors[] = 'Slug cannot be empty.';
        } elseif (get_page_by_path($new_slug) && get_page_by_path($new_slug)->ID != $subscriber_page_id) {
            $errors[] = 'This slug is already taken. Please choose another.';
        }
        if (empty($errors)) {
            // Update content and slug
            wp_update_post([
                'ID' => $subscriber_page_id,
                'post_content' => $new_content,
                'post_name' => $new_slug
            ]);
            if ($selected_template_id) {
                // Get current page info
                $old_page = get_post($subscriber_page_id);
                $old_slug = $old_page->post_name;
                $old_title = $old_page->post_title;
                $old_author = $old_page->post_author;
                // Delete the old page
                wp_delete_post($subscriber_page_id, true);
                // Create a new page with the same slug, author, and settings
                $new_page_data = array(
                    'post_title'    => $old_title,
                    'post_name'     => $old_slug,
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                    'post_author'   => $old_author,
                    'post_content'  => '',
                    'page_template' => 'elementor_canvas'
                );
                $new_page_id = wp_insert_post($new_page_data);
                if ($new_page_id && !is_wp_error($new_page_id)) {
                    // Assign the selected template using the Template widget
                    $template_widget = [
                        [
                            'id' => uniqid(),
                            'elType' => 'widget',
                            'widgetType' => 'template',
                            'settings' => [
                                'template_id' => $selected_template_id,
                            ],
                            'elements' => [],
                            'isInner' => false,
                        ]
                    ];
                    update_post_meta($new_page_id, '_elementor_data', wp_slash(json_encode($template_widget)));
                    update_post_meta($new_page_id, '_elementor_edit_mode', 'builder');
                    update_post_meta($new_page_id, '_elementor_template_type', 'wp-page');
                    update_post_meta($new_page_id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.0.0');
                    update_user_meta($user_id, 'subscriber_page_id', $new_page_id);
                    update_user_meta($user_id, 'subscriber_page_url', $old_slug);
                    update_user_meta($user_id, 'subscriber_selected_template', $selected_template_id);
                    // Redirect to the edit screen for the new page
                    wp_redirect(get_permalink($new_page_id));
                    exit;
                }
            }
            $message = 'Page updated successfully!';
        } else {
            $message = implode(' ', $errors);
        }
    }
    // Refresh the page object after update
    $subscriber_page = get_post($subscriber_page_id);
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

Timber::render(['page--subscriber-dashboard-edit.twig', 'page.twig'], $context); 