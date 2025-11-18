<?php
/**
 * API Test Script
 * Tests if api_get_reports.php returns valid JSON
 */

echo "🧪 Testing API: api_get_reports.php\n";
echo str_repeat("=", 50) . "\n\n";

// Test 1: Check if file exists
echo "✓ Test 1: File exists\n";
if (!file_exists(__DIR__ . '/api_get_reports.php')) {
    die("❌ FAILED: api_get_reports.php not found\n");
}
echo "  ✅ PASSED\n\n";

// Test 2: Check if db_connect.php exists
echo "✓ Test 2: Database connection file exists\n";
if (!file_exists(__DIR__ . '/components/db_connect.php')) {
    die("❌ FAILED: components/db_connect.php not found\n");
}
echo "  ✅ PASSED\n\n";

// Test 3: Simulate API call
echo "✓ Test 3: Fetching API response\n";
$url = 'http://localhost/Webby/api_get_reports.php';
$response = @file_get_contents($url);

if ($response === false) {
    echo "  ⚠️  WARNING: Could not fetch from $url\n";
    echo "  Make sure XAMPP Apache is running!\n\n";
} else {
    echo "  ✅ PASSED: Response received\n\n";
    
    // Test 4: Validate JSON
    echo "✓ Test 4: Validating JSON structure\n";
    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "  ❌ FAILED: Invalid JSON - " . json_last_error_msg() . "\n";
        echo "  Response preview:\n";
        echo "  " . substr($response, 0, 200) . "...\n\n";
        exit(1);
    }
    
    echo "  ✅ PASSED: Valid JSON\n\n";
    
    // Test 5: Check response structure
    echo "✓ Test 5: Checking response structure\n";
    if (!isset($data['success']) || !isset($data['data'])) {
        echo "  ❌ FAILED: Missing 'success' or 'data' keys\n";
        print_r($data);
        exit(1);
    }
    echo "  ✅ PASSED: Response has 'success' and 'data' keys\n\n";
    
    // Test 6: Display results
    echo "✓ Test 6: API Results\n";
    echo "  Success: " . ($data['success'] ? 'true' : 'false') . "\n";
    echo "  Reports count: " . count($data['data']) . "\n";
    
    if (count($data['data']) > 0) {
        echo "  Sample report fields:\n";
        $sample = $data['data'][0];
        foreach ($sample as $key => $value) {
            $displayValue = is_string($value) ? substr($value, 0, 50) : $value;
            echo "    - $key: $displayValue\n";
        }
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎉 ALL TESTS PASSED!\n";
    echo "API is working correctly and returning valid JSON.\n";
}
?>
