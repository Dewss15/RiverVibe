<?php
header('Content-Type: application/json');
require_once 'components/db_connect.php';

// Get filter parameters
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$pollution_type = isset($_GET['pollution_type']) ? $_GET['pollution_type'] : 'all';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build SQL query
$sql = "SELECT * FROM river_reports WHERE 1=1";

// Apply severity filter
if ($filter !== 'all') {
    $sql .= " AND severity = '" . $conn->real_escape_string($filter) . "'";
}

// Apply pollution type filter
if ($pollution_type !== 'all') {
    $sql .= " AND pollution_type = '" . $conn->real_escape_string($pollution_type) . "'";
}

// Apply search filter
if (!empty($search)) {
    $search_escaped = $conn->real_escape_string($search);
    $sql .= " AND (river_name LIKE '%$search_escaped%' 
              OR location LIKE '%$search_escaped%' 
              OR pollution_type LIKE '%$search_escaped%'
              OR description LIKE '%$search_escaped%')";
}

// Order by most recent first
$sql .= " ORDER BY reported_date DESC";

$result = $conn->query($sql);

$reports = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = [
            'id' => $row['id'],
            'river_name' => $row['river_name'],
            'location' => $row['location'],
            'pollution_type' => $row['pollution_type'],
            'severity' => $row['severity'],
            'description' => $row['description'],
            'photo_url' => $row['photo_url'],
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'status' => $row['status'],
            'reported_date' => $row['reported_date'],
            'user_id' => $row['user_id'],
            'water_quality_index' => $row['water_quality_index'],
            'ph_level' => $row['ph_level'],
            'dissolved_oxygen' => $row['dissolved_oxygen'],
            'turbidity' => $row['turbidity'],
            'temperature' => $row['temperature']
        ];
    }
}

echo json_encode([
    'success' => true,
    'count' => count($reports),
    'reports' => $reports
]);

$conn->close();
?>
