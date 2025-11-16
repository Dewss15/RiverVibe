<?php
session_start();

// Destroy all session data
session_destroy();

// Redirect to homepage with success message
header('Location: /Webby/index.php?logout=success');
exit();
?>
