<?php

/**
 *  Template Name:  Register
 */


// Basic debugging - check if form is submitted
if ($_POST) {
    error_log('Form submitted! POST data: ' . print_r($_POST, true));
}

// Handle user registration
function handle_user_registration()
{
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
    $phone_number = sanitize_text_field($_POST['phone_number'] ?? '');

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

        // Ensure subscriber role exists (it's a default WordPress role)
        error_log('Using default subscriber role');

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

            // Save phone number if provided
            if (!empty($phone_number)) {
                update_user_meta($user_id, 'phone_number', $phone_number);
                error_log("Phone number saved for user $user_id: $phone_number");
            }

            // Assign the subscriber role
            error_log('Assigning subscriber role...');
            $user = new WP_User($user_id);
            $user->set_role('subscriber');

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
            'last_name' => $last_name,
            'phone_number' => $phone_number
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
    $context['old_phone_number'] = $registration_result['old_data']['phone_number'] ?? '';
}

// Generate nonce for the form
$context['registration_nonce'] = wp_create_nonce('user_registration_nonce');

error_log('About to render template...');
Timber::render(['page--register.twig', 'page.twig'], $context);
