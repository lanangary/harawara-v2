<?php
/**
 * Template Name: Subscriber Dashboard
 */

// Check if user is logged in and is a subscriber
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$user = wp_get_current_user();
if (!in_array('subscriber', $user->roles)) {
    wp_redirect(home_url());
    exit;
}

// Get subscriber dashboard content
$dashboard_content = get_subscriber_dashboard_content($user);

$context = Timber::context();
$context['dashboard_content'] = $dashboard_content;
$context['user'] = $user;

Timber::render(['page--subscriber-dashboard.twig', 'page.twig'], $context); 