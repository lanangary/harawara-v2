<?php
//Include composer autoloader
include ABSPATH . "../../vendor/autoload.php";

use Timber\Timber;

use JuiceBox\Core\Site;
use JuiceBox\Core\Admin;
use JuiceBox\Core\GravityForms;
use JuiceBox\Core\ACFJson;
use JuiceBox\Core\DefaultContent;
use JuiceBox\Core\Accessibility;
use JuiceBox\Core\SEO;
//use JuiceBox\Core\Agile;

use JuiceBox\Config\Shortcodes;
use JuiceBox\Config\ThemeSupport;
use JuiceBox\Config\CustomPostTypes;
use JuiceBox\Config\CustomTaxonomies;
use JuiceBox\Config\Menus;
use JuiceBox\Config\Assets;

Timber::init();

/**
 * ------------------
 * Core
 * ------------------
 */
if(class_exists('\JuiceBox\Core\Site')){

    // Site Config
    new Site();
    new Admin();
    new ACFJson();
    new Accessibility();
    new SEO();
    new DefaultContent();
    new GravityForms();
    //new Agile();
}

/**
 * ------------------
 * Config
 * ------------------
 */
if(class_exists('\JuiceBox\Config\Assets')){

    // Register Custom Taxonomies
    CustomTaxonomies::register();

    // Register CPT
    CustomPostTypes::register();

    // Register WordPress menus
    Menus::register();

    // Load JS/CSS
    Assets::load();

    // Shortcodes.
    Shortcodes::register();

    // Register theme support.
    ThemeSupport::register();
}

/**
 * ------------------
 * User Registration & Permissions
 * ------------------
 */

// Update page_editor role capabilities to include edit_published_pages
function update_page_editor_role_capabilities() {
    $role = get_role('page_editor');
    if ($role) {
        $role->add_cap('edit_published_pages');
        error_log('Updated page_editor role to include edit_published_pages capability');
    }
}
add_action('init', 'update_page_editor_role_capabilities');

// Preserve original author when pages are published by administrators
function preserve_page_author_on_publish($post_id, $post, $update) {
    // Only apply to pages
    if ($post->post_type !== 'page') {
        return;
    }

    // Only apply when publishing
    if ($post->post_status !== 'publish') {
        return;
    }

    // Check if this is a page_editor's page being published by an admin
    $current_user = wp_get_current_user();
    $post_author = get_user_by('id', $post->post_author);
    
    if ($post_author && in_array('page_editor', $post_author->roles) && 
        !in_array('page_editor', $current_user->roles)) {
        
        // Store the original author if not already stored
        $original_author = get_post_meta($post_id, '_original_author', true);
        if (!$original_author) {
            update_post_meta($post_id, '_original_author', $post->post_author);
            error_log("Preserved original author {$post->post_author} for page {$post_id}");
        }
    }
}
add_action('wp_insert_post', 'preserve_page_author_on_publish', 10, 3);

// Allow page_editors to edit pages they originally created, even if published by admin
function allow_edit_original_pages($allcaps, $caps, $args) {
    // Only apply to page_editor role
    if (!in_array('page_editor', wp_get_current_user()->roles)) {
        return $allcaps;
    }

    $user_id = get_current_user_id();
    $post_id = isset($args[2]) ? $args[2] : 0;

    // If trying to edit a page
    if (isset($args[0]) && in_array($args[0], ['edit_post', 'delete_post', 'publish_post', 'edit_published_pages'])) {
        $post = get_post($post_id);
        
        if ($post && $post->post_type === 'page') {
            // Check if user is the current author OR the original author
            $original_author = get_post_meta($post_id, '_original_author', true);
            
            if ($post->post_author == $user_id || $original_author == $user_id) {
                $allcaps[$args[0]] = true;
            } else {
                $allcaps[$args[0]] = false;
            }
        }
    }

    return $allcaps;
}
add_filter('user_has_cap', 'allow_edit_original_pages', 20, 3);

// Filter pages list to show only user's own pages
function filter_user_pages($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $user = wp_get_current_user();
    if (in_array('page_editor', $user->roles)) {
        if ($query->get('post_type') === 'page') {
            // Show pages where user is current author OR original author
            global $wpdb;
            
            // Get pages where user is the original author
            $original_author_pages = $wpdb->get_col($wpdb->prepare(
                "SELECT post_id FROM {$wpdb->postmeta} 
                WHERE meta_key = '_original_author' AND meta_value = %d",
                $user->ID
            ));
            
            // Combine current author pages with original author pages
            $user_pages = array_merge(
                [$user->ID], // Current author
                $original_author_pages // Original author
            );
            
            // Set the author parameter to include all user's pages
            $query->set('author__in', $user_pages);
        }
    }
}
add_action('pre_get_posts', 'filter_user_pages');

// Restrict media library to show only user's own uploads
function filter_user_media($query) {
    // Only apply to page_editor role
    $user = wp_get_current_user();
    if (!in_array('page_editor', $user->roles)) {
        return;
    }

    // Check if we're in admin and dealing with attachments
    if (is_admin() && $query->get('post_type') === 'attachment') {
        $query->set('author', $user->ID);
        return;
    }

    // Also handle AJAX requests for media library
    if (wp_doing_ajax() && isset($_REQUEST['action']) && $_REQUEST['action'] === 'query-attachments') {
        $query->set('author', $user->ID);
        return;
    }
}
add_action('pre_get_posts', 'filter_user_media', 10);

// Additional filter for AJAX media queries
function filter_ajax_media_query($query) {
    $user = wp_get_current_user();
    if (!in_array('page_editor', $user->roles)) {
        return $query;
    }

    // Filter AJAX media library queries
    if (wp_doing_ajax() && isset($_REQUEST['action']) && $_REQUEST['action'] === 'query-attachments') {
        $query['author'] = $user->ID;
    }

    return $query;
}
add_filter('ajax_query_attachments_args', 'filter_ajax_media_query');

// Direct filter for media library query
function filter_media_library_directly($query) {
    $user = wp_get_current_user();
    if (!in_array('page_editor', $user->roles)) {
        return;
    }

    // Check if this is a media library query
    if (is_admin() && 
        (($query->get('post_type') === 'attachment') || 
         (isset($_GET['post_type']) && $_GET['post_type'] === 'attachment'))) {
        
        // Set the author to current user
        $query->set('author', $user->ID);
        
        // Also filter by author in the WHERE clause
        add_filter('posts_where', function($where) use ($user) {
            global $wpdb;
            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_author = %d", $user->ID);
            return $where;
        });
    }
}
add_action('pre_get_posts', 'filter_media_library_directly', 999);

// Debug function to check media filtering (remove in production)
function debug_media_filtering() {
    $user = wp_get_current_user();
    if (in_array('page_editor', $user->roles) && isset($_GET['post_type']) && $_GET['post_type'] === 'attachment') {
        add_action('admin_notices', function() use ($user) {
            echo '<div class="notice notice-info"><p>Debug: Filtering media for user ID: ' . $user->ID . ' (User: ' . $user->user_login . ')</p></div>';
        });
    }
}
add_action('admin_init', 'debug_media_filtering');

// Additional filter for media library page
function filter_media_library_page() {
    $user = wp_get_current_user();
    if (!in_array('page_editor', $user->roles)) {
        return;
    }

    // Only apply on media library page
    if (isset($_GET['post_type']) && $_GET['post_type'] === 'attachment') {
        add_filter('posts_where', function($where) use ($user) {
            global $wpdb;
            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_author = %d", $user->ID);
            return $where;
        });
    }
}
add_action('admin_init', 'filter_media_library_page');

// Clear media cache for page_editor users
function clear_media_cache_for_page_editor() {
    $user = wp_get_current_user();
    if (in_array('page_editor', $user->roles)) {
        // Clear any cached media queries
        wp_cache_flush();
        
        // Force refresh of media library
        if (isset($_GET['post_type']) && $_GET['post_type'] === 'attachment') {
            add_action('admin_head', function() {
                echo '<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">';
                echo '<meta http-equiv="Pragma" content="no-cache">';
                echo '<meta http-equiv="Expires" content="0">';
            });
        }
    }
}
add_action('admin_init', 'clear_media_cache_for_page_editor');

// Filter media library in admin
function filter_admin_media_library() {
    $user = wp_get_current_user();
    if (!in_array('page_editor', $user->roles)) {
        return;
    }

    // Add JavaScript to filter media library
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Filter media library grid view
        if (typeof wp !== 'undefined' && wp.media) {
            wp.media.view.AttachmentsBrowser.prototype.initialize = function() {
                var original = wp.media.view.AttachmentsBrowser.prototype.initialize;
                return function() {
                    original.apply(this, arguments);
                    this.collection.props.set('author', <?php echo $user->ID; ?>);
                };
            }();
        }
    });
    </script>
    <?php
}
add_action('admin_footer', 'filter_admin_media_library');

// Restrict media upload capabilities for page_editor role
function restrict_media_capabilities($allcaps, $caps, $args) {
    // Only apply to page_editor role
    if (!in_array('page_editor', wp_get_current_user()->roles)) {
        return $allcaps;
    }

    $user_id = get_current_user_id();

    // If trying to edit/delete an attachment
    if (isset($args[0]) && in_array($args[0], ['edit_post', 'delete_post'])) {
        $post_id = isset($args[2]) ? $args[2] : 0;
        $post = get_post($post_id);
        
        // Only allow if it's an attachment and the user is the author
        if ($post && $post->post_type === 'attachment' && $post->post_author == $user_id) {
            $allcaps[$args[0]] = true;
        } else {
            $allcaps[$args[0]] = false;
        }
    }

    return $allcaps;
}
add_filter('user_has_cap', 'restrict_media_capabilities', 10, 3);

// Add custom meta box to show page author
function add_page_author_meta_box() {
    add_meta_box(
        'page_author',
        'Page Author',
        'display_page_author_meta_box',
        'page',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_page_author_meta_box');

function display_page_author_meta_box($post) {
    $author = get_userdata($post->post_author);
    echo '<p><strong>Author:</strong> ' . esc_html($author->display_name) . '</p>';
    echo '<p><strong>Email:</strong> ' . esc_html($author->user_email) . '</p>';
}

// Add custom meta box to show media author
function add_media_author_meta_box() {
    add_meta_box(
        'media_author',
        'Media Author',
        'display_media_author_meta_box',
        'attachment',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'add_media_author_meta_box');

function display_media_author_meta_box($post) {
    $author = get_userdata($post->post_author);
    echo '<p><strong>Uploaded by:</strong> ' . esc_html($author->display_name) . '</p>';
    echo '<p><strong>Email:</strong> ' . esc_html($author->user_email) . '</p>';
    echo '<p><strong>Date:</strong> ' . esc_html(get_the_date('F j, Y', $post->ID)) . '</p>';
}

// Disable admin bar for page_editor role (optional)
function disable_admin_bar_for_page_editors() {
    $user = wp_get_current_user();
    if (in_array('page_editor', $user->roles)) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'disable_admin_bar_for_page_editors');

// Redirect page_editor users to frontend after login
function redirect_page_editor_after_login($redirect_to, $request, $user) {
    // Check if user is valid and not a WP_Error
    if (is_wp_error($user) || !$user || !is_object($user)) {
        return $redirect_to;
    }
    
    // Check if user has roles property and it's an array
    if (!isset($user->roles) || !is_array($user->roles)) {
        return $redirect_to;
    }
    
    if (in_array('page_editor', $user->roles)) {
        // Allow redirects to admin area (like after registration)
        if (strpos($redirect_to, 'wp-admin') !== false || strpos($redirect_to, admin_url()) !== false) {
            return $redirect_to;
        }
        // For regular logins, redirect to homepage
        return home_url();
    }
    return $redirect_to;
}
add_filter('login_redirect', 'redirect_page_editor_after_login', 10, 3);
