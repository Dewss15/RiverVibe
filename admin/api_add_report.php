<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Prevent output before JSON
ob_start();

// Set headers
header('Content-Type: application/json; charset=utf-8');

// Load database connection
require_once __DIR__ . '/../components/db_connect.php';

// Initialize response
$response = [
    'success' => false,
    'error' => null,
    'report_id' => null
];

try {
    // Validate required fields
    $required_fields = ['river_name', 'location', 'pollution_type', 'status', 'description', 'date_submitted'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    // Sanitize inputs
    $river_name = $conn->real_escape_string(trim($_POST['river_name']));
    $location = $conn->real_escape_string(trim($_POST['location']));
    $pollution_type = $conn->real_escape_string(trim($_POST['pollution_type']));
    $status = $conn->real_escape_string(trim($_POST['status']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $date_submitted = $conn->real_escape_string(trim($_POST['date_submitted']));
    $location_coordinates = isset($_POST['location_coordinates']) ? $conn->real_escape_string(trim($_POST['location_coordinates'])) : 'Not available';
    $reported_by = isset($_POST['reported_by']) ? $conn->real_escape_string(trim($_POST['reported_by'])) : 'Admin';
    $authority_response = '';
    $user_id = $_SESSION['user_id'];
    
    // Handle image upload
    $image_filename = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/';
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = $_FILES['image']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WebP images are allowed.');
        }
        
        // Validate file size (max 5MB)
        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            throw new Exception('File size too large. Maximum size is 5MB.');
        }
        
        // Generate unique filename
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_filename = 'report_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $image_filename;
        
        // Move uploaded file
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            throw new Exception('Failed to upload image file.');
        }
    }
    
    // Get base URL for QR code generation
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $base_url = $protocol . '://' . $host;
    
    // Insert report into database (without QR code first to get the ID)
    $sql = "INSERT INTO river_reports (
        river_name, 
        location, 
        pollution_type, 
        status, 
        description, 
        date_submitted, 
        reported_by, 
        location_coordinates, 
        authority_response, 
        user_id, 
        image,
        created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param(
        'sssssssssss',
        $river_name,
        $location,
        $pollution_type,
        $status,
        $description,
        $date_submitted,
        $reported_by,
        $location_coordinates,
        $authority_response,
        $user_id,
        $image_filename
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert report: ' . $stmt->error);
    }
    
    // Get the newly inserted report ID
    $new_report_id = $stmt->insert_id;
    
    // Generate QR code URL
    $qr_code_url = $base_url . '/Webby/dashboard.php?id=' . $new_report_id;
    
    // Update the report with QR code URL
    $update_sql = "UPDATE river_reports SET qr_code = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    
    if ($update_stmt) {
        $update_stmt->bind_param('si', $qr_code_url, $new_report_id);
        $update_stmt->execute();
        $update_stmt->close();
    }
    
    $stmt->close();
    $conn->close();
    
    // Success response
    $response['success'] = true;
    $response['report_id'] = $new_report_id;
    $response['message'] = 'Report added successfully';
    
} catch (Exception $e) {
    // Error response
    error_log('Add Report Error: ' . $e->getMessage());
    $response['success'] = false;
    $response['error'] = $e->getMessage();
    
    // Clean up uploaded file if database insert failed
    if (isset($image_filename) && file_exists(__DIR__ . '/../uploads/' . $image_filename)) {
        unlink(__DIR__ . '/../uploads/' . $image_filename);
    }
}

// Clear buffer and output JSON
ob_clean();
echo json_encode($response);
ob_end_flush();
exit;
