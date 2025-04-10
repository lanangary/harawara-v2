<?php

// Database credentials
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'fresh2'; // Update with your WordPress database name
$tablePrefix = 'cgpywoz_'; // Use the prefix from your .env file

// New site URL
$newSiteUrl = 'https://balinale.local.local.box';
// https://fresh-theme.local.box/

// Connect to the database
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error . "\n");
}

// Update the siteurl option
$updateSiteUrlQuery = "UPDATE {$tablePrefix}options SET option_value = '$newSiteUrl' WHERE option_name = 'siteurl'";
if ($mysqli->query($updateSiteUrlQuery) === TRUE) {
    echo "Updated 'siteurl' to '$newSiteUrl'.\n";
} else {
    echo "Error updating 'siteurl': " . $mysqli->error . "\n";
}

// Update the home option
$updateHomeQuery = "UPDATE {$tablePrefix}options SET option_value = '$newSiteUrl' WHERE option_name = 'home'";
if ($mysqli->query($updateHomeQuery) === TRUE) {
    echo "Updated 'home' to '$newSiteUrl'.\n";
} else {
    echo "Error updating 'home': " . $mysqli->error . "\n";
}

$mysqli->close();
