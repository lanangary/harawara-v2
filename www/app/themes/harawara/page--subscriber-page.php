<?php
/**
 * Template Name: Subscriber Page
 */

// Check if user is logged in and is the author of this page
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$current_user = wp_get_current_user();
$page_id = get_the_ID();
$page_author_id = get_post_field('post_author', $page_id);

// Only allow the page author to view this page
// if ($current_user->ID != $page_author_id) {
//     wp_redirect(home_url());
//     exit;
// }

// Handle frontend form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscriber_page_update'])) {
    // Security check
    if (!wp_verify_nonce($_POST['subscriber_page_nonce'], 'subscriber_page_update_' . $page_id)) {
        $message = 'Security check failed. Please try again.';
    } else {
        $new_content = wp_kses_post($_POST['page_content']);
        $new_slug = sanitize_title($_POST['page_slug']);
        $errors = [];

        // Validate slug
        if (empty($new_slug)) {
            $errors[] = 'Slug cannot be empty.';
        } elseif (get_page_by_path($new_slug) && get_page_by_path($new_slug)->ID != $page_id) {
            $errors[] = 'This slug is already taken. Please choose another.';
        }

        if (empty($errors)) {
            // Update content
            wp_update_post([
                'ID' => $page_id,
                'post_content' => $new_content,
                'post_name' => $new_slug
            ]);
            $message = 'Page updated successfully!';
        } else {
            $message = implode(' ', $errors);
        }
    }
}

$context = Timber::context();
$context['page_owner'] = get_user_by('id', $page_author_id);
$context['is_owner'] = ($current_user->ID == $page_author_id);
$context['edit_message'] = $message;
$context['edit_slug'] = get_post_field('post_name', $page_id);
$context['edit_content'] = get_post_field('post_content', $page_id);
$context['edit_nonce'] = wp_create_nonce('subscriber_page_update_' . $page_id);

Timber::render(['page--subscriber-page.twig', 'page.twig'], $context); 