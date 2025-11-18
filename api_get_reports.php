<?php
// Prevent ANY output before JSON
ob_start();

// Suppress error display (log errors instead)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Set JSON header FIRST
header('Content-Type: application/json; charset=utf-8');

// Load database connection
require_once __DIR__ . '/components/db_connect.php';

// Initialize response structure
$response = [
    "success" => false,
    "data" => [],
    "error" => null
];

try {
    // Verify database connection exists
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection failed");
    }
    
    // Execute SQL query
    $sql = "SELECT * FROM river_reports ORDER BY id ASC";
    $result = $conn->query($sql);
    
    if ($result === false) {
        throw new Exception("Query execution failed: " . $conn->error);
    }
    
    // Process results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Build report object
            $response["data"][] = [
                "id" => (int)$row["id"],
                "river_name" => $row["river_name"] ?? '',
                "location" => $row["location"] ?? '',
                "pollution_type" => $row["pollution_type"] ?? '',
                "description" => $row["description"] ?? '',
                "image" => $row["image"] ?? null,
                "status" => $row["status"] ?? 'pending',
                "date_submitted" => $row["date_submitted"] ?? '',
                "reported_by" => $row["reported_by"] ?? '',
                "authority_response" => $row["authority_response"] ?? '',
                "location_coordinates" => !empty($row["location_coordinates"]) ? $row["location_coordinates"] : 'Not available',
                "user_id" => isset($row["user_id"]) ? (int)$row["user_id"] : null,
                "created_at" => $row["created_at"] ?? ''
            ];
        }
        
        $response["success"] = true;
    }
    
    // Close connection
    if (isset($conn)) {
        $conn->close();
    }
    
} catch (Exception $e) {
    // Log error securely
    error_log("API Error: " . $e->getMessage());
    
    $response["success"] = false;
    $response["error"] = "Failed to fetch reports";
    $response["data"] = [];
}

// Clear any buffered output
ob_clean();

// Output ONLY valid JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Flush and end
ob_end_flush();
exit;
