<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Webby/login.php");
    exit;
}

require_once __DIR__ . '/../components/db_connect.php';

$report_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($report_id > 0) {
    // Delete the report
    $stmt = $conn->prepare("DELETE FROM river_reports WHERE id = ?");
    $stmt->bind_param("i", $report_id);
    
    if ($stmt->execute()) {
        $_SESSION['delete_success'] = "Report deleted successfully!";
    } else {
        $_SESSION['delete_error'] = "Error deleting report.";
    }
    $stmt->close();
}

header("Location: /Webby/admin/view_reports.php");
exit;
?>
