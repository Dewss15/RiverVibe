<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Webby/login.php");
    exit;
}

require_once __DIR__ . '/../components/db_connect.php';

// Get parameters - check POST first (for modal form), then GET (for direct links)
$feedback_id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);
$new_status = isset($_POST['status']) ? $_POST['status'] : (isset($_GET['status']) ? $_GET['status'] : '');
$admin_notes = isset($_POST['admin_notes']) ? trim($_POST['admin_notes']) : null;

// Validate feedback ID
if ($feedback_id <= 0) {
    $_SESSION['error_message'] = "Invalid feedback ID.";
    header("Location: /Webby/admin/view_feedback.php");
    exit;
}

// Validate status
$allowed_statuses = ['Pending', 'Reviewing', 'Reviewed', 'Flagged'];
if (!in_array($new_status, $allowed_statuses)) {
    $_SESSION['error_message'] = "Invalid status value.";
    header("Location: /Webby/admin/view_feedback.php");
    exit;
}

// Update feedback status
if ($admin_notes !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update both status and notes
    $stmt = $conn->prepare("UPDATE feedback SET status = ?, admin_notes = ? WHERE id = ?");
    $stmt->bind_param("ssi", $new_status, $admin_notes, $feedback_id);
} else {
    // Update only status
    $stmt = $conn->prepare("UPDATE feedback SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $feedback_id);
}

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Feedback status updated to <strong>" . htmlspecialchars($new_status) . "</strong> successfully!";
} else {
    $_SESSION['error_message'] = "Error updating feedback status: " . $conn->error;
}

$stmt->close();
$conn->close();

// Redirect back to view feedback page
header("Location: /Webby/admin/view_feedback.php");
exit;
?>
