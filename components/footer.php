<?php
// Detect if this is an admin page
$is_admin_page = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
?>

<!-- 🌊 FOOTER -->
<footer class="footer">
  <div class="social-links">
    <a href="https://facebook.com/rivervibe" target="_blank" aria-label="Facebook">
      <i class="fab fa-facebook"></i>
    </a>
    <a href="https://twitter.com/rivervibe" target="_blank" aria-label="Twitter">
      <i class="fab fa-twitter"></i>
    </a>
    <a href="https://instagram.com/rivervibe" target="_blank" aria-label="Instagram">
      <i class="fab fa-instagram"></i>
    </a>
    <a href="https://linkedin.com/company/rivervibe" target="_blank" aria-label="LinkedIn">
      <i class="fab fa-linkedin"></i>
    </a>
  </div>
  <p class="footer-slogan">One river cleaned. A thousand lives changed.</p>
  <p class="footer-credit">&copy; <?php echo date('Y'); ?> River Vibe. Built with ❤️ for our planet.</p>
</footer>

<!-- Theme Toggle Button (Floating - appears on all pages) -->
<button id="theme-toggle" class="theme-floating-btn" aria-label="Toggle dark mode">
  <i class="fas fa-moon"></i>
</button>

<?php if (!$is_admin_page): ?>
<!-- PUBLIC PAGE SCRIPTS -->
<!-- Three.js CDN (for hero background on homepage) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

<!-- Core JavaScript Files - EXACT ORDER REQUIRED -->
<script src="/Webby/js/home.js"></script>
<script src="/Webby/js/app.js"></script>

<?php 
// Only load dashboard-unified.js on dashboard/report pages
$current_page = basename($_SERVER['PHP_SELF']);
$dashboard_pages = ['dashboard.php', 'my_reports.php'];
if (in_array($current_page, $dashboard_pages)): 
?>
<script src="/Webby/js/dashboard-unified.js"></script>
<?php endif; ?>

<?php endif; ?>

<!-- Page-specific scripts -->
<?php if (isset($additional_scripts) && is_array($additional_scripts)): ?>
  <?php foreach ($additional_scripts as $script): ?>
    <?php echo $script; ?>
  <?php endforeach; ?>
<?php endif; ?>

<!-- Scroll Animations (loads on all pages) -->
<script src="/Webby/js/scroll-animations.js"></script>

<!-- Theme.js MUST load LAST for proper initialization -->
<script src="/Webby/js/theme.js"></script>

</body>
</html>

