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

/**
 * ------------------
 * Custom User Profile Fields
 * ------------------
 */

// Add phone number field to user profile
function add_phone_number_field($user) {
    ?>
    <h3>Contact Information</h3>
    <table class="form-table">
        <tr>
            <th><label for="phone_number">Phone Number</label></th>
            <td>
                <input type="tel" name="phone_number" id="phone_number" 
                       value="<?php echo esc_attr(get_user_meta($user->ID, 'phone_number', true)); ?>" 
                       class="regular-text" />
                <p class="description">Enter your phone number (optional)</p>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'add_phone_number_field');
add_action('edit_user_profile', 'add_phone_number_field');

// Save phone number field
function save_phone_number_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    
    if (isset($_POST['phone_number'])) {
        update_user_meta($user_id, 'phone_number', sanitize_text_field($_POST['phone_number']));
    }
}
add_action('personal_options_update', 'save_phone_number_field');
add_action('edit_user_profile_update', 'save_phone_number_field');

// Add phone number to registration form
function add_phone_to_registration_form() {
    ?>
    <div class="form-group">
        <label for="phone_number">Phone Number</label>
        <input type="tel" id="phone_number" name="phone_number" class="form-control" value="<?php echo esc_attr($_POST['phone_number'] ?? ''); ?>">
        <small class="form-text text-muted">Enter your phone number (optional)</small>
    </div>
    <?php
}

// Save phone number during registration
function save_phone_number_on_registration($user_id) {
    if (isset($_POST['phone_number']) && !empty($_POST['phone_number'])) {
        update_user_meta($user_id, 'phone_number', sanitize_text_field($_POST['phone_number']));
    }
}
add_action('user_register', 'save_phone_number_on_registration');

// Add phone number to user list table in admin
function add_phone_column_to_users($columns) {
    $columns['phone_number'] = 'Phone Number';
    return $columns;
}
add_filter('manage_users_columns', 'add_phone_column_to_users');

// Display phone number in user list table
function display_phone_column_content($value, $column_name, $user_id) {
    if ($column_name === 'phone_number') {
        $phone = get_user_meta($user_id, 'phone_number', true);
        return $phone ? esc_html($phone) : '—';
    }
    return $value;
}
add_action('manage_users_custom_column', 'display_phone_column_content', 10, 3);

// Make phone number column sortable
function make_phone_column_sortable($columns) {
    $columns['phone_number'] = 'phone_number';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'make_phone_column_sortable');

// Handle sorting for phone number column
function sort_users_by_phone($query) {
    if (is_admin() && isset($_GET['orderby']) && $_GET['orderby'] === 'phone_number') {
        $query->set('meta_key', 'phone_number');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_users', 'sort_users_by_phone');

/**
 * ------------------
 * Subscriber Role Access Control
 * ------------------
 */

// Redirect subscribers to their dedicated page after login
function redirect_subscribers_after_login($redirect_to, $request, $user) {
    // Check if user is valid and not a WP_Error
    if (is_wp_error($user) || !$user || !is_object($user)) {
        return $redirect_to;
    }
    
    // Check if user has roles property and it's an array
    if (!isset($user->roles) || !is_array($user->roles)) {
        return $redirect_to;
    }
    
    if (in_array('subscriber', $user->roles)) {
        // Allow redirects to admin area (like after registration)
        if (strpos($redirect_to, 'wp-admin') !== false || strpos($redirect_to, admin_url()) !== false) {
            return $redirect_to;
        }
        // For regular logins, redirect to subscriber dashboard
        $subscriber_page = get_page_by_path('subscriber-dashboard');
        if ($subscriber_page) {
            return get_permalink($subscriber_page->ID);
        }
        return home_url();
    }
    return $redirect_to;
}
add_filter('login_redirect', 'redirect_subscribers_after_login', 10, 3);

// Restrict subscriber access to admin area
function restrict_subscriber_admin_access() {
    if (is_admin() && !current_user_can('edit_posts') && !wp_doing_ajax()) {
        $user = wp_get_current_user();
        if (in_array('subscriber', $user->roles)) {
            $subscriber_page = get_page_by_path('subscriber-dashboard');
            if ($subscriber_page) {
                wp_redirect(get_permalink($subscriber_page->ID));
                exit;
            } else {
                wp_redirect(home_url());
                exit;
            }
        }
    }
}
add_action('init', 'restrict_subscriber_admin_access');

// Add subscriber dashboard to admin menu for subscribers
function add_subscriber_dashboard_menu() {
    $user = wp_get_current_user();
    if (in_array('subscriber', $user->roles)) {
        $subscriber_page = get_page_by_path('subscriber-dashboard');
        if ($subscriber_page) {
            add_menu_page(
                'Subscriber Dashboard',
                'My Dashboard',
                'read',
                'subscriber-dashboard',
                function() use ($subscriber_page) {
                    wp_redirect(get_permalink($subscriber_page->ID));
                    exit;
                },
                'dashicons-admin-home',
                1
            );
        }
    }
}
add_action('admin_menu', 'add_subscriber_dashboard_menu');

// Create subscriber dashboard page if it doesn't exist
function create_subscriber_dashboard_page() {
    $page_exists = get_page_by_path('subscriber-dashboard');
    
    if (!$page_exists) {
        $page_data = array(
            'post_title'    => 'Subscriber Dashboard',
            'post_name'     => 'subscriber-dashboard',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_content'  => 'Welcome to your subscriber dashboard!',
            'page_template' => 'page--subscriber-dashboard.php'
        );
        
        $page_id = wp_insert_post($page_data);
        
        if ($page_id) {
            error_log("Subscriber dashboard page created with ID: $page_id");
        }
    }
}
add_action('init', 'create_subscriber_dashboard_page');

// Add subscriber-specific content to dashboard
function get_subscriber_dashboard_content($user) {
    $phone = get_user_meta($user->ID, 'phone_number', true);
    $content = '<div class="subscriber-dashboard">';
    $content .= '<h2>Welcome, ' . esc_html($user->display_name) . '!</h2>';
    $content .= '<div class="user-info">';
    $content .= '<p><strong>Email:</strong> ' . esc_html($user->user_email) . '</p>';
    if ($phone) {
        $content .= '<p><strong>Phone:</strong> ' . esc_html($phone) . '</p>';
    }
    $content .= '<p><strong>Member since:</strong> ' . date('F j, Y', strtotime($user->user_registered)) . '</p>';
    $content .= '</div>';
    
    // Add subscriber page info
    $content .= add_subscriber_page_info_to_dashboard($user);
    
    // Add subscriber-specific features here
    $content .= '<div class="subscriber-features">';
    $content .= '<h3>Your Benefits</h3>';
    $content .= '<ul>';
    $content .= '<li>Access to exclusive content</li>';
    $content .= '<li>Special member discounts</li>';
    $content .= '<li>Priority customer support</li>';
    $content .= '<li>Your own personal page to customize</li>';
    $content .= '</ul>';
    $content .= '</div>';
    
    $content .= '</div>';
    
    return $content;
}

/**
 * ------------------
 * Subscriber Page Management
 * ------------------
 */

// Create individual page for each subscriber
function create_subscriber_page($user_id) {
    $user = get_user_by('id', $user_id);
    if (!$user || !in_array('subscriber', $user->roles)) {
        return false;
    }
    // Check if subscriber already has a page
    $existing_page = get_user_meta($user_id, 'subscriber_page_id', true);
    if ($existing_page && get_post($existing_page)) {
        return $existing_page;
    }
    // Create custom URL slug
    $username = sanitize_title($user->user_login);
    $page_slug = 'member-' . $username;
    $counter = 1;
    $original_slug = $page_slug;
    while (get_page_by_path($page_slug)) {
        $page_slug = $original_slug . '-' . $counter;
        $counter++;
    }
    // Create the page with Elementor Canvas template
    $page_data = array(
        'post_title'    => $user->display_name . "'s Page",
        'post_name'     => $page_slug,
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => $user_id,
        'post_content'  => '',
        'page_template' => 'elementor_canvas'
    );
    $page_id = wp_insert_post($page_data);
    if ($page_id && !is_wp_error($page_id)) {
        // Assign Elementor template (\, ID 135)
        $jiwa_template_id = 135;
        $jiwa_data = get_post_meta($jiwa_template_id, '_elementor_data', true);
        if ($jiwa_data) {
            update_post_meta($page_id, '_elementor_data', $jiwa_data);
        }
        update_post_meta($page_id, '_elementor_edit_mode', 'builder');
        update_post_meta($page_id, '_elementor_template_type', 'wp-page');
        update_post_meta($page_id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.0.0');
        update_user_meta($user_id, 'subscriber_page_id', $page_id);
        update_user_meta($user_id, 'subscriber_page_url', $page_slug);
        error_log("Created subscriber page for user $user_id with ID: $page_id and slug: $page_slug");
        return $page_id;
    }
    return false;
}

// Get default content for subscriber page
function get_default_subscriber_page_content($user) {
    $content = '<div class="subscriber-page-content">';
    $content .= '<h2>Welcome to ' . esc_html($user->display_name) . "'s Page</h2>";
    $content .= '<p>This is your personal page where you can share information about yourself.</p>';
    $content .= '<div class="member-info">';
    $content .= '<h3>About Me</h3>';
    $content .= '<p>Tell visitors about yourself here...</p>';
    $content .= '</div>';
    $content .= '<div class="member-contact">';
    $content .= '<h3>Contact Information</h3>';
    $content .= '<p>Email: ' . esc_html($user->user_email) . '</p>';
    $phone = get_user_meta($user->ID, 'phone_number', true);
    if ($phone) {
        $content .= '<p>Phone: ' . esc_html($phone) . '</p>';
    }
    $content .= '</div>';
    $content .= '</div>';
    
    return $content;
}

// Create subscriber page when user is created
function create_subscriber_page_on_registration($user_id) {
    $user = get_user_by('id', $user_id);
    if ($user && in_array('subscriber', $user->roles)) {
        create_subscriber_page($user_id);
    }
}
add_action('user_register', 'create_subscriber_page_on_registration');

// Add subscriber page editing capabilities
function add_subscriber_page_capabilities() {
    $subscriber_role = get_role('subscriber');
    if ($subscriber_role) {
        $subscriber_role->add_cap('edit_pages');
        $subscriber_role->add_cap('edit_published_pages');
    }
}
add_action('init', 'add_subscriber_page_capabilities');

// Allow subscribers to edit only their own page
function restrict_subscriber_page_editing($allcaps, $caps, $args) {
    if (!in_array('subscriber', wp_get_current_user()->roles)) {
        return $allcaps;
    }
    
    $user_id = get_current_user_id();
    $post_id = isset($args[2]) ? $args[2] : 0;
    
    // If trying to edit a page
    if (isset($args[0]) && in_array($args[0], ['edit_post', 'edit_pages', 'edit_published_pages'])) {
        $post = get_post($post_id);
        
        if ($post && $post->post_type === 'page') {
            // Check if this is the subscriber's own page
            $subscriber_page_id = get_user_meta($user_id, 'subscriber_page_id', true);
            
            if ($post->ID == $subscriber_page_id) {
                $allcaps[$args[0]] = true;
            } else {
                $allcaps[$args[0]] = false;
            }
        }
    }
    
    return $allcaps;
}
add_filter('user_has_cap', 'restrict_subscriber_page_editing', 20, 3);

// Filter pages list to show only subscriber's own page
function filter_subscriber_pages($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    $user = wp_get_current_user();
    if (in_array('subscriber', $user->roles)) {
        if ($query->get('post_type') === 'page') {
            $subscriber_page_id = get_user_meta($user->ID, 'subscriber_page_id', true);
            if ($subscriber_page_id) {
                $query->set('p', $subscriber_page_id);
            }
        }
    }
}
add_action('pre_get_posts', 'filter_subscriber_pages');

// Add subscriber page info to dashboard
function add_subscriber_page_info_to_dashboard($user) {
    $page_id = get_user_meta($user->ID, 'subscriber_page_id', true);
    $page_url = get_user_meta($user->ID, 'subscriber_page_url', true);
    
    if ($page_id && $page_url) {
        $page = get_post($page_id);
        if ($page) {
            $content = '<div class="subscriber-page-info">';
            $content .= '<h3>Your Personal Page</h3>';
            $content .= '<p><strong>Page Title:</strong> ' . esc_html($page->post_title) . '</p>';
            $content .= '<p><strong>Page URL:</strong> <a href="' . get_permalink($page_id) . '" target="_blank">' . get_permalink($page_id) . '</a></p>';
            $content .= '<p><strong>Last Updated:</strong> ' . get_the_modified_date('F j, Y', $page_id) . '</p>';
            $content .= '<div class="page-actions">';
            $content .= '<a href="' . get_edit_post_link($page_id) . '" class="btn btn-primary">Edit Your Page</a>';
            $content .= '<a href="' . get_permalink($page_id) . '" class="btn btn-secondary" target="_blank">View Your Page</a>';
            $content .= '</div>';
            $content .= '</div>';
            
            return $content;
        }
    }
    
    return '<div class="subscriber-page-info"><p>Your personal page is being created...</p></div>';
}
