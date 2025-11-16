<?php
session_start();
$page_title = "River Pollution Dashboard - RiverVibe";

// Additional CSS files for dashboard
$additional_css = ['<link rel="stylesheet" href="/Webby/css/reports-dynamic.css">'];

// Additional JavaScript files for dashboard (dashboard-unified.js now loaded in footer.php)
$additional_scripts = [];

include 'components/header.php';
?>

  <!-- 🎯 HERO SECTION WITH GRADIENT & ANIMATED STATS -->
  <section class="reports-hero">
    <div class="hero-content animate-fade-in">
      <h1 class="gradient-text">River Pollution Dashboard</h1>
      <p class="hero-subtitle">Track, monitor, and act on river pollution across India</p>
      
      <!-- Animated Stat Bubbles - Dynamic from Database -->
      <?php
      require_once 'components/db_connect.php';
      
      // Get statistics from database
      $total_reports = 0;
      $pending_reports = 0;
      $verified_reports = 0;
      $resolved_reports = 0;
      
      // Count total reports
      $result = $conn->query("SELECT COUNT(*) as total FROM river_reports");
      if ($row = $result->fetch_assoc()) {
          $total_reports = $row['total'];
      }
      
      // Count pending reports
      $result = $conn->query("SELECT COUNT(*) as total FROM river_reports WHERE status = 'pending'");
      if ($row = $result->fetch_assoc()) {
          $pending_reports = $row['total'];
      }
      
      // Count verified reports
      $result = $conn->query("SELECT COUNT(*) as total FROM river_reports WHERE status = 'verified'");
      if ($row = $result->fetch_assoc()) {
          $verified_reports = $row['total'];
      }
      
      // Count resolved reports
      $result = $conn->query("SELECT COUNT(*) as total FROM river_reports WHERE status = 'resolved'");
      if ($row = $result->fetch_assoc()) {
          $resolved_reports = $row['total'];
      }
      
      ?>
      
      <div class="stats-quick">
        <div class="stat-bubble">
          <span class="stat-number" data-target="<?php echo $total_reports; ?>">0</span>
          <span class="stat-label">Total Reports</span>
        </div>
        <div class="stat-bubble">
          <span class="stat-number" data-target="<?php echo $pending_reports; ?>">0</span>
          <span class="stat-label">Pending</span>
        </div>
        <div class="stat-bubble">
          <span class="stat-number" data-target="<?php echo $verified_reports; ?>">0</span>
          <span class="stat-label">Verified</span>
        </div>
        <div class="stat-bubble">
          <span class="stat-number" data-target="<?php echo $resolved_reports; ?>">0</span>
          <span class="stat-label">Resolved</span>
        </div>
      </div>
    </div>

    <!-- Animated Wave SVG -->
    <div class="wave-animation">
      <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
        <path fill="var(--bg-primary)" fill-opacity="0.3" d="M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,128C672,107,768,85,864,90.7C960,96,1056,128,1152,133.3C1248,139,1344,117,1392,106.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
      </svg>
    </div>
  </section>

  <!-- 🔍 SEARCH & FILTER SECTION -->
  <section class="filter-section">
    <div class="container">
      <div class="filter-controls animate-slide-up">
        
        <!-- Search Bar with Icon -->
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input 
            type="text" 
            id="searchInput" 
            placeholder="Search by river name, location, or pollution type..." 
            aria-label="Search reports">
        </div>
        
        <!-- Filter Buttons (All / Pending / Verified / Resolved) -->
        <div class="filter-buttons">
          <button class="filter-btn active" data-filter="all">
            <i class="fas fa-globe"></i> All Reports
          </button>
          <button class="filter-btn" data-filter="pending">
            <i class="fas fa-clock"></i> Pending
          </button>
          <button class="filter-btn" data-filter="verified">
            <i class="fas fa-check-circle"></i> Verified
          </button>
          <button class="filter-btn" data-filter="resolved">
            <i class="fas fa-circle-check"></i> Resolved
          </button>
        </div>

        <!-- Pollution Type Dropdown Filter -->
        <div class="pollution-filter">
          <select id="pollutionTypeFilter" aria-label="Filter by pollution type">
            <option value="all">All Pollution Types</option>
            <option value="Plastic">Plastic Waste</option>
            <option value="Chemical">Chemical Discharge</option>
            <option value="Sewage">Sewage & Domestic</option>
            <option value="Industrial">Industrial Waste</option>
            <option value="Agricultural">Agricultural</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>
    </div>
  </section>

  <!-- 📊 DYNAMIC REPORTS GRID SECTION -->
  <section class="reports-grid-section">
    <div class="container">
      
      <!-- Loading Spinner (shows while fetching data) -->
      <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner-ring"></div>
        <p>Loading reports...</p>
      </div>

      <!-- Reports Grid (Dynamic cards injected by JavaScript) -->
      <div class="reports-grid" id="reportsGrid">
        <!-- Dynamic report cards will be injected here via dashboard-unified.js -->
      </div>

      <!-- Empty State (if no results found) -->
      <div class="empty-state" id="emptyState" style="display: none;">
        <i class="fas fa-search"></i>
        <h3>No reports found</h3>
        <p>Try adjusting your search or filter criteria</p>
      </div>
    </div>
  </section>

  <!-- 🔝 FLOATING BACK TO TOP BUTTON -->
  <button id="backToTop" class="back-to-top" aria-label="Back to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- 📱 FULL REPORT MODAL (Detailed Water Quality Data, Timeline, Authority Info) -->
  <div id="reportDetailModal" class="modal-overlay">
    <div class="modal-container">
      <button class="modal-close" onclick="closeReportModal()" aria-label="Close modal">
        <i class="fas fa-times"></i>
      </button>
      <div class="modal-content" id="modalContent">
        <!-- Dynamic content injected by JavaScript -->
      </div>
    </div>
  </div>

  <!-- 🔳 QR CODE MODAL (Generate & Download QR for Sharing) -->
  <div id="qrModal" class="modal-overlay">
    <div class="modal-container qr-modal">
      <button class="modal-close" onclick="closeQRModal()" aria-label="Close QR modal">
        <i class="fas fa-times"></i>
      </button>
      <div class="modal-content qr-content">
        <div class="qr-header">
          <i class="fas fa-qrcode"></i>
          <h2>Share This Report</h2>
        </div>
        <div class="qr-code-container" id="qrCodeContainer">
          <!-- QR code image injected here -->
        </div>
        <p class="qr-instructions">Scan this QR code to view the report on your mobile device</p>
        <button class="download-qr-btn" id="downloadQRBtn">
          <i class="fas fa-download"></i> Download QR Code
        </button>
      </div>
    </div>
  </div>

<?php include 'components/footer.php'; ?>
