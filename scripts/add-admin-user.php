<?php

// Database credentials
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'fresh2'; // Update with your WordPress database name
$tablePrefix = 'cgpywoz_'; // Use the prefix from your .env file

// New user details
$username = 'badrock';
$password = 'badrock123123123';
$email = 'badrock@example.com'; // Update with a valid email address
$role = 'administrator';

// Connect to the database
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error . "\n");
}

// Delete all users
$deleteAllUsersQuery = "DELETE FROM {$tablePrefix}users";
if ($mysqli->query($deleteAllUsersQuery) === TRUE) {
    echo "Deleted all existing users.\n";

    // Delete all usermeta entries
    $deleteAllUserMetaQuery = "DELETE FROM {$tablePrefix}usermeta";
    if ($mysqli->query($deleteAllUserMetaQuery) === TRUE) {
        echo "Deleted all usermeta entries.\n";
    } else {
        echo "Error deleting usermeta entries: " . $mysqli->error . "\n";
    }
} else {
    echo "Error deleting users: " . $mysqli->error . "\n";
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert the new user into the users table
$insertUserQuery = "
    INSERT INTO {$tablePrefix}users (ID, user_login, user_pass, user_email, user_registered, user_status, display_name)
    VALUES (1 ,'$username', '$hashedPassword', '$email', NOW(), 0, '$username')
";

if ($mysqli->query($insertUserQuery) === TRUE) {
    $userId = $mysqli->insert_id;
    echo "User '$username' created successfully with ID $userId.\n";

    // Assign the administrator role in the usermeta table
    $insertUserMetaQuery = "
        INSERT INTO {$tablePrefix}usermeta (user_id, meta_key, meta_value)
        VALUES
        ($userId, '{$tablePrefix}capabilities', 'a:1:{s:13:\"administrator\";b:1;}'),
        ($userId, '{$tablePrefix}user_level', '10')
    ";

    if ($mysqli->query($insertUserMetaQuery) === TRUE) {
        echo "User '$username' assigned the role of '$role'.\n";
    } else {
        echo "Error assigning role: " . $mysqli->error . "\n";
    }
} else {
    echo "Error creating user: " . $mysqli->error . "\n";
}

$mysqli->close();
