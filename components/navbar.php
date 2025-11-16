<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- 🌊 NAVIGATION BAR -->
<nav>
  <div class="logo">RiverVibe</div>
  <ul>
    <li><a href="/Webby/index.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>Home</a></li>
    <li><a href="/Webby/about.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'class="active"' : ''; ?>>About</a></li>
    <li><a href="/Webby/dashboard.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'class="active"' : ''; ?>>Dashboard</a></li>
    <li><a href="/Webby/feedback.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'feedback.php') ? 'class="active"' : ''; ?>>Feedback</a></li>
    <li><a href="/Webby/success.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'success.php') ? 'class="active"' : ''; ?>>Success Stories</a></li>
    
    <?php if (isset($_SESSION['user_id'])): ?>
      <!-- Logged in user -->
      <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'): ?>
        <!-- Show My Reports only for regular users, not admins -->
        <li><a href="/Webby/my_reports.php">My Reports</a></li>
      <?php endif; ?>
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
        <li><a href="/Webby/admin/index.php">Admin Panel</a></li>
      <?php endif; ?>
      <li><a href="/Webby/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a></li>
    <?php endif; ?>
  </ul>
</nav>
