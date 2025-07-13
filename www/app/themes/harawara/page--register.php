<?php
/**
 *  Template Name:  Register
 */

// Debug: Confirm this template is loaded
error_log('Register template loaded for URL: ' . $_SERVER['REQUEST_URI']);

// Basic debugging - check if form is submitted
if ($_POST) {
    error_log('Form submitted! POST data: ' . print_r($_POST, true));
}

// Handle user registration
function handle_user_registration() {
    // Debug: Check if form is submitted
    if (!isset($_POST['register_user'])) {
        error_log('No register_user in POST data');
        return null;
    }

    error_log('Registration form submitted!');

    // Debug: Check if user registration is enabled
    if (!get_option('users_can_register')) {
        error_log('User registration is disabled');
        return [
            'status' => 'error',
            'message' => 'User registration is disabled on this site.',
            'old_data' => []
        ];
    }

    error_log('User registration is enabled');

    // Debug: Check if nonce is valid
    if (!wp_verify_nonce($_POST['registration_nonce'], 'user_registration_nonce')) {
        error_log('Nonce verification failed');
        return [
            'status' => 'error',
            'message' => 'Security check failed. Please try again.',
            'old_data' => []
        ];
    }

    error_log('Nonce verification passed');

    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);

    error_log("Processing registration for username: $username, email: $email");

    $errors = [];

    // Validation
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (username_exists($username)) {
        $errors[] = 'Username already exists.';
    }

    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!is_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    } elseif (email_exists($email)) {
        $errors[] = 'Email already exists.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    error_log('Validation completed. Errors: ' . count($errors));

    // If no errors, create the user
    if (empty($errors)) {
        error_log('No validation errors, creating user...');
        
        // Create custom role if it doesn't exist
        if (!get_role('page_editor')) {
            error_log('Creating page_editor role...');
            add_role('page_editor', 'Page Editor', [
                'read' => true,
                'edit_posts' => false,
                'delete_posts' => false,
                'publish_posts' => false,
                'edit_pages' => true,
                'delete_pages' => false,
                'publish_pages' => false,
                'edit_others_pages' => false,
                'delete_others_pages' => false,
                'edit_private_pages' => false,
                'delete_private_pages' => false,
                'edit_published_pages' => true,
                'delete_published_pages' => false,
                'upload_files' => true,
            ]);
            error_log('page_editor role created');
        } else {
            error_log('page_editor role already exists');
        }

        // Create the user
        error_log('Calling wp_create_user...');
        $user_id = wp_create_user($username, $password, $email);

        if (!is_wp_error($user_id)) {
            error_log("User created successfully with ID: $user_id");
            
            // Update user meta
            error_log('Updating user meta...');
            wp_update_user([
                'ID' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'display_name' => $first_name . ' ' . $last_name
            ]);

            // Assign the custom role
            error_log('Assigning page_editor role...');
            $user = new WP_User($user_id);
            $user->set_role('page_editor');

            // Auto-login the user
            error_log('Setting current user and auth cookie...');
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);

            error_log('Registration successful, redirecting...');
            // Redirect to dashboard or success page
            wp_redirect(admin_url());
            exit;
        } else {
            error_log('User creation failed: ' . $user_id->get_error_message());
            $errors[] = $user_id->get_error_message();
        }
    } else {
        error_log('Validation errors: ' . implode(', ', $errors));
    }

    error_log('Returning result with status: ' . (empty($errors) ? 'success' : 'error'));
    return [
        'status' => empty($errors) ? 'success' : 'error',
        'message' => empty($errors) ? 'Registration successful!' : implode('<br>', $errors),
        'old_data' => [
            'username' => $username,
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name
        ]
    ];
}

// Process registration
error_log('About to call handle_user_registration...');
$registration_result = handle_user_registration();
error_log('handle_user_registration returned: ' . print_r($registration_result, true));

$context = Timber::context();

// Add registration data to context
if ($registration_result) {
    error_log('Adding registration result to context...');
    $context['registration_status'] = $registration_result['status'];
    $context['registration_message'] = $registration_result['message'];
    $context['old_username'] = $registration_result['old_data']['username'] ?? '';
    $context['old_email'] = $registration_result['old_data']['email'] ?? '';
    $context['old_first_name'] = $registration_result['old_data']['first_name'] ?? '';
    $context['old_last_name'] = $registration_result['old_data']['last_name'] ?? '';
}

// Generate nonce for the form
$context['registration_nonce'] = wp_create_nonce('user_registration_nonce');

error_log('About to render template...');
Timber::render(['page--register.twig', 'page.twig'], $context);
