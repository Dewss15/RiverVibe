<?php
/**
 * Database Connection Component
 * RiverVibe Project - MySQL Database Connection
 * 
 * IMPORTANT: Update these credentials for your local environment
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Update with your MySQL password
define('DB_NAME', 'rivervibe_db');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Optional: Uncomment for debugging
// echo "<!-- Database connected successfully -->";
?>
