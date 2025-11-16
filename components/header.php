<?php
// Detect if this is an admin page
$is_admin_page = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;

// Set default page title if not set
if (!isset($page_title)) {
    $page_title = "RiverVibe - Report River Pollution";
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Base CSS - Always loaded -->
    <link rel="stylesheet" href="/Webby/css/style.css">
    
    <!-- Additional CSS -->
    <?php 
    if (isset($additional_css) && is_array($additional_css)): 
        foreach ($additional_css as $css): 
            echo $css . "\n";
        endforeach;
    endif;
    ?>
</head>
<body>

<?php if (!$is_admin_page): ?>
    <?php include __DIR__ . '/navbar.php'; ?>
<?php endif; ?>
