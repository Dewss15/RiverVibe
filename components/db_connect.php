<?php
/**
 * Database Connection Component
 * RiverVibe Project - MySQL Database Connection
 * 
 * IMPORTANT: Update these credentials for your local environment
 */

// Database configuration
define('DB_HOST', 'localhost'); // Update if your DB host is different
define('DB_USER', 'root');
define('DB_PASS', ''); // Update with your MySQL password
define('DB_NAME', 'rivervibe_db');

// Check if database exists, create if not
$conn_temp = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($conn_temp->connect_error) {
    error_log("Connection failed: " . $conn_temp->connect_error);
    http_response_code(500);
    exit(1);
}

$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn_temp->query($sql) === FALSE) {
    error_log("Error creating database: " . $conn_temp->error);
}
$conn_temp->close();

// Connect to database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    http_response_code(500);
    exit(1);
}

$conn->set_charset("utf8mb4");
