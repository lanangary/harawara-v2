<?php
// mayar-webhook.php
// Bootstrap WordPress using the same method as the main index.php
define('WP_USE_THEMES', false);
require dirname(dirname(dirname(__DIR__))) . '/wp/wp-blog-header.php';

// Read raw POST body and decode JSON
$input = file_get_contents('php://input');
error_log('Raw input: ' . $input);

$data = json_decode($input, true);
if (!$data || !isset($data['data'])) {
    error_log('json_decode error: ' . json_last_error_msg());
    http_response_code(400);
    echo 'Invalid JSON';
    exit;
}

// Extract relevant fields
$status = $data['data']['status'] ?? '';
$email = $data['data']['customerEmail'] ?? '';
$phone = $data['data']['customerMobile'] ?? '';
$customer_name = $data['data']['customerName'] ?? '';

// Only process successful payments
if ($status === 'SUCCESS' && $email) {
    $eligible = get_option('mayar_paid_users', []);
    $merchant_name = $data['data']['merchantName'] ?? '';
    $eligible[$email] = [
        'phone' => $phone,
        'registered' => false,
        'paid_at' => date('Y-m-d H:i:s'),
        'customer_name' => $customer_name,
        'merchant_name' => $merchant_name,
        'raw' => $data['data'] // Store full data for reference
    ];
    update_option('mayar_paid_users', $eligible);

    // Check if user already exists
    $user = get_user_by('email', $email);
    if (!$user) {
        // Create new user
        $user_id = wp_create_user($email, $phone, $email);
        if (is_wp_error($user_id)) {
            error_log('User creation error: ' . $user_id->get_error_message());
            http_response_code(500);
            echo 'User creation failed';
            exit;
        }
        error_log("New user created with ID: $user_id for email: $email");
    } else {
        // Update existing user's password and name
        $user_id = $user->ID;
        wp_set_password($phone, $user_id);
        error_log("Existing user updated with ID: $user_id for email: $email");
    }
    // Set display name as merchantName
    wp_update_user([
        'ID' => $user_id,
        'first_name' => $customer_name,
        'display_name' => $merchant_name
    ]);

    // Save phone number to user meta if provided
    if (!empty($phone)) {
        update_user_meta($user_id, 'phone_number', $phone);
        error_log("Phone number saved for user $user_id: $phone");
    }
    http_response_code(200);
    echo 'OK';
} else {
    http_response_code(400);
    echo 'Invalid or unpaid';
}
