/********************************************************************************************
 * 🌊 RIVERVIBE - DYNAMIC REPORTS JAVASCRIPT
 * ------------------------------------------------------------------------------------------
 * Handles:
 * - Dynamic data fetching from backend/mock data
 * - Search and filter functionality with smooth animations
 * - QR code generation for each report
 * - Modal popups with detailed report view
 * - Scroll animations and IntersectionObserver
 * - Number counter animations
 * - Back to top button
 * - Share functionality
 * ------------------------------------------------------------------------------------------
 * COLOR SCHEME: #0096c7 (Primary), #00b4d8 (Accent), #023e8a (Deep), #80ffdb (Neon)
 * FONT: Poppins (300-700)
 ********************************************************************************************/

// ===== 1. MOCK DATA (Replace with fetch from backend in production) =====

/**
 * Mock river pollution reports data
 * In production: Replace with fetch(`get_reports.php`) or Supabase query
 */
/*
const mockReportsData = [
  {
    id: 1,
    riverName: 'Ganga',
    location: 'Varanasi Ghat Area, Uttar Pradesh',
    pollutionType: 'chemical',
    description: 'Multiple instances of chemical discharge observed near the industrial area. Water shows visible discoloration and strong chemical odor. Local wildlife affected. Immediate attention required.',
    photo: '/Webby/uploads/ganga.jpg',
    status: 'verified',
    dateSubmitted: 'November 10, 2025',
    reportedBy: 'Citizens Group',
    authorityResponse: 'Municipal authorities have been notified. Investigation team deployed to assess the situation. Cleanup operations scheduled for next week.',
    coordinates: '25.3176° N, 82.9739° E',
    severity: 'critical'
  },
  {
    id: 2,
    riverName: 'Yamuna',
    location: 'Okhla Barrage, Delhi',
    pollutionType: 'sewage',
    description: 'Heavy sewage discharge continues to pollute the river. Foam formation and black water observed. Urgent intervention needed to address untreated sewage disposal.',
    photo: '/Webby/uploads/yamuna.jpg',
    status: 'pending',
    dateSubmitted: 'November 11, 2025',
    reportedBy: 'Environmental NGO',
    authorityResponse: 'Report under review by Delhi Pollution Control Committee.',
    coordinates: '28.5244° N, 77.3109° E',
    severity: 'critical'
  },
  {
    id: 3,
    riverName: 'Mithi',
    location: 'Kurla-BKC Area, Mumbai',
    pollutionType: 'plastic',
    description: 'Significant accumulation of plastic waste and debris observed. Local clean-up initiatives ongoing but requiring more support.',
    photo: '/Webby/uploads/mithi.jpg',
    status: 'resolved',
    dateSubmitted: 'November 8, 2025',
    reportedBy: 'Local Resident',
    authorityResponse: 'Cleanup drive completed successfully. Regular monitoring established.',
    coordinates: '19.0728° N, 72.8826° E',
    severity: 'medium'
  },
  {
    id: 4,
    riverName: 'Sabarmati',
    location: 'Vasna Barrage Area, Ahmedabad',
    pollutionType: 'industrial',
    description: 'Industrial discharge causing water quality deterioration. Fish mortality reported in several areas. Immediate action required.',
    photo: '/Webby/uploads/sabarmati.jpg',
    status: 'verified',
    dateSubmitted: 'November 9, 2025',
    reportedBy: 'Fishermen Association',
    authorityResponse: 'Industries issued notice. Water quality monitoring increased.',
    coordinates: '23.0225° N, 72.5714° E',
    severity: 'critical'
  },
  {
    id: 5,
    riverName: 'Brahmaputra',
    location: 'Guwahati Port Area, Assam',
    pollutionType: 'other',
    description: 'Oil slicks spotted near port area. Urban waste accumulation increasing. Local authorities notified for cleanup.',
    photo: '/Webby/uploads/brahmaputri.jpg',
    status: 'pending',
    dateSubmitted: 'November 12, 2025',
    reportedBy: 'Port Workers',
    authorityResponse: '',
    coordinates: '26.1445° N, 91.7362° E',
    severity: 'medium'
  },
  {
    id: 6,
    riverName: 'Cauvery',
    location: 'Srirangapatna Region, Karnataka',
    pollutionType: 'sewage',
    description: 'Excessive agricultural runoff causing algal blooms. Sewage discharge from urban areas affecting water quality. Comprehensive action plan needed.',
    photo: '/Webby/uploads/cauvery.png',
    status: 'verified',
    dateSubmitted: 'November 7, 2025',
    reportedBy: 'Agricultural Department',
    authorityResponse: 'Inter-state coordination meeting scheduled. Treatment plants being upgraded.',
    coordinates: '12.4184° N, 76.6947° E',
    severity: 'critical'
  },
  {
    id: 7,
    riverName: 'Godavari',
    location: 'Ramkund, Nashik, Maharashtra',
    pollutionType: 'plastic',
    description: 'Increased religious waste disposal during festival season. Plastic accumulation in sacred spots. Community awareness programs initiated.',
    photo: '/Webby/uploads/godavari.jpg',
    status: 'resolved',
    dateSubmitted: 'November 5, 2025',
    reportedBy: 'Temple Committee',
    authorityResponse: 'Awareness campaign launched. Biodegradable alternatives provided.',
    coordinates: '19.9975° N, 73.7898° E',
    severity: 'medium'
  },
  {
    id: 8,
    riverName: 'Narmada',
    location: 'Bedaghat Region, Madhya Pradesh',
    pollutionType: 'other',
    description: 'Illegal sand mining activities affecting river ecology. Construction debris disposal observed. Local authorities monitoring situation.',
    photo: '/Webby/uploads/narmada.jpg',
    status: 'pending',
    dateSubmitted: 'November 6, 2025',
    reportedBy: 'Wildlife Conservation Group',
    authorityResponse: '',
    coordinates: '23.1424° N, 79.8137° E',
    severity: 'medium'
  }
];

// Store all reports and filtered results
let allReports = [];
let filteredReports = [];

// Fallback image URL
const fallbackURL = '/Webby/uploads/default-river.jpg'; // Define fallback image

// ===== 2. INITIALIZATION =====

/**
 * Initialize the reports page when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', () => {
  console.log('🌊 RiverVibe Reports - Initializing...');
  
  // Check if we're on a page with report elements
  const reportsGrid = document.getElementById('reportsGrid');
  const loadingSpinner = document.getElementById('loadingSpinner');
  
  if (!reportsGrid || !loadingSpinner) {
    console.log('⚠️ Report elements not found - skipping fetchReports()');
    return;
  }
  
  // Fetch and display reports
  fetchReports();
  
  // Initialize event listeners
  initializeEventListeners();
  
  // Initialize scroll animations
  initializeScrollAnimations();
  
  // Initialize number counters
  animateCounters();
  
  console.log('✅ Reports page initialized successfully!');
});

// ===== AUTO-OPEN MODAL FROM QR CODE =====
/**
 * When URL contains ?id=X, automatically open that report's modal
 * This enables QR code scanning to directly open the report details
 */
(function() {
  // Check if URL has an id parameter
  if (!window.location.search.includes('id=')) {
    console.log('📍 No report ID in URL - normal dashboard load');
    return;
  }

  const params = new URLSearchParams(window.location.search);
  const requestedId = params.get('id');
  
  console.log('🔍 QR Code scan detected - Report ID:', requestedId);
  
  let attempts = 0;
  const maxAttempts = 50; // 50 attempts × 200ms = 10 seconds timeout
  const checkInterval = 200; // Check every 200ms
  
  const waitForReports = setInterval(() => {
    attempts++;
    
    // Check if reports are loaded
    if (typeof allReports !== 'undefined' && 
        Array.isArray(allReports) && 
        allReports.length > 0) {
      
      clearInterval(waitForReports);
      console.log(`✅ Reports loaded (${allReports.length} total) after ${attempts * checkInterval}ms`);
      
      // Find the report with matching ID (strict string comparison)
      const foundReport = allReports.find(r => String(r.id) === String(requestedId));
      
      if (foundReport) {
        console.log('✅ Report found:', {
          id: foundReport.id,
          riverName: foundReport.riverName,
          location: foundReport.location
        });
        
        // Small delay to ensure DOM is fully ready
        setTimeout(() => {
          console.log('🚀 Opening modal for report ID:', requestedId);
          viewFullReport(foundReport.id);
        }, 100);
        
      } else {
        console.warn('⚠️ Report not found with ID:', requestedId);
        console.log('Available report IDs:', allReports.map(r => r.id));
      }
      
      return;
    }
    
    // Timeout after max attempts
    if (attempts >= maxAttempts) {
      clearInterval(waitForReports);
      console.error('❌ Timeout: Reports failed to load within 10 seconds');
      console.log('Debug - allReports state:', {
        defined: typeof allReports !== 'undefined',
        isArray: Array.isArray(allReports),
        length: allReports ? allReports.length : 0
      });
    }
    
  }, checkInterval);
  
  console.log('⏳ Waiting for reports to load...');
})();

// ===== 3. DATA FETCHING =====

/**
 * Fetch reports from backend or use mock data
 * In production: Replace with actual API call
 */
async function fetchReports() {
  const loadingSpinner = document.getElementById('loadingSpinner');
  const reportsGrid = document.getElementById('reportsGrid');
  let dataLoaded = false; // Track if data was successfully loaded
  
  try {
    // Show loading spinner
    loadingSpinner.style.display = 'flex';
    
    // IMPORTANT: Check the correct API path
    const response = await fetch('/Webby/api_get_reports.php');
    
    // Log response for debugging
    console.log('API Response Status:', response.status);
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    console.log('API Data:', data); // Debug log
    
    // ✓ Test 1: Verify data structure
    if (!data.success || !data.data || !Array.isArray(data.data)) {
      throw new Error('Invalid API response structure');
    }
    
    // Mark that data was successfully loaded
    dataLoaded = true;
    
    if (data.data.length === 0) {
      console.warn('⚠️ No reports found in database');
      allReports = [];
      filteredReports = [];
    } else {
      // Transform database format to match frontend expectations
      allReports = data.data.map((report, index) => {
        // ✓ Test 2: Validate required fields exist
        if (!report.id || !report.river_name || !report.pollution_type) {
          console.warn(`⚠️ Report ${index} missing required fields:`, report);
        }
        
        // ✓ Test 3: Validate and construct image path
        const imagePath = report.image ? `/Webby/uploads/${report.image}` : fallbackURL;
        
        // ✓ Test 4: Validate date parsing
        let dateSubmitted = 'Date not available';
        try {
          if (report.date_submitted) {
            dateSubmitted = new Date(report.date_submitted).toLocaleDateString('en-US', { 
              year: 'numeric', 
              month: 'long', 
              day: 'numeric' 
            });
          }
        } catch (e) {
          console.warn(`⚠️ Invalid date for report ${report.id}:`, report.date_submitted);
        }
        
        // ✓ Test 5: Construct and validate QR URL
        const reportUrl = `${window.location.origin}/Webby/dashboard.php?id=${report.id}`;
        if (!reportUrl.includes(report.id)) {
          console.error(`❌ QR URL missing ID for report ${report.id}`);
        }
        
        return {
          id: report.id,
          riverName: report.river_name,
          location: report.location,
          pollutionType: report.pollution_type ? report.pollution_type.toLowerCase() : 'other',
          description: report.description || 'No description provided',
          status: report.status || 'pending',
          image: imagePath,
          dateSubmitted: dateSubmitted,
          reportedBy: report.reported_by || `User #${report.user_id}`,
          authorityResponse: report.authority_response || '',
          coordinates: report.location_coordinates || 'Not available',
          qrCodeUrl: reportUrl,
          qrCode: report['qr-code'] || '',
          userId: report.user_id,
          createdAt: report.created_at
        };
      });
      
      filteredReports = [...allReports];
      
      // ✓ Test 6: Final validation summary
      console.log('✓ API mapping validated');
      console.log(`✓ ${allReports.length} reports loaded successfully`);
      console.log('✓ Sample report structure:', allReports[0]);
    }
    
  } catch (error) {
    console.error('❌ Error fetching reports:', error);
    
    // Only show error notification if data failed to load
    if (!dataLoaded) {
      showNotification('Failed to load reports. Please check console for details.', 'error');
    }
    
    // Fallback: Set empty array if no data loaded
    if (!dataLoaded) {
      allReports = [];
      filteredReports = [];
    }
  } finally {
    // Always hide loading spinner
    loadingSpinner.style.display = 'none';
    
    // Render reports (even if empty)
    renderReports(filteredReports);
  }
}

// ===== 4. RENDER REPORTS =====

/**
 * Render report cards in the grid
 * @param {Array} reports - Array of report objects to display
 */
function renderReports(reports) {
  const reportsGrid = document.getElementById('reportsGrid');
  const emptyState = document.getElementById('emptyState');
  
  // Clear existing content
  reportsGrid.innerHTML = '';
  
  // Show empty state if no reports
  if (reports.length === 0) {
    emptyState.style.display = 'block';
    return;
  }
  
  emptyState.style.display = 'none';
  
  // Create and append report cards
  reports.forEach((report, index) => {
    const card = createReportCard(report, index);
    reportsGrid.appendChild(card);
  });
  
  // Re-trigger scroll animations for new cards
  observeCards();
}

/**
 * Create a single report card element
 * @param {Object} report - Report data object
 * @param {Number} index - Card index for animation delay
 * @returns {HTMLElement} - Report card element
 */
function createReportCard(report, index) {
  const card = document.createElement('div');
  // Add status class to card for filtering
  const statusClass = report.status.toLowerCase();
  card.className = `report-card ${statusClass}`;
  card.style.animationDelay = `${index * 0.1}s`;
  card.dataset.reportId = report.id;
  card.dataset.status = report.status;
  card.dataset.pollutionType = report.pollutionType;
  
  // Status badge class
  const statusBadgeClass = `status-${report.status.toLowerCase()}`;
  
  // Generate QR code URL
  const reportUrl = `${window.location.origin}/Webby/dashboard.php?id=${report.id}`;
  const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(reportUrl)}&size=120x120`;
  
  card.innerHTML = `
    <img src="${report.image}" alt="${report.riverName} River" class="report-card-image" loading="lazy">
    <div class="report-card-content">
      <div class="report-card-header">
        <h3 class="report-river-name">${report.riverName}</h3>
        <span class="report-status-badge ${statusBadgeClass}">${report.status}</span>
      </div>
      
      <div class="report-location">
        <i class="fas fa-map-marker-alt"></i>
        <span>${report.location}</span>
      </div>
      
      <span class="report-pollution-type">
        <i class="fas fa-flask"></i> ${formatPollutionType(report.pollutionType)}
      </span>
      
      <p class="report-description">${report.description}</p>
      
      <div class="report-meta">
        <span class="report-date">
          <i class="fas fa-calendar"></i>
          ${report.dateSubmitted}
        </span>
        
        <div class="report-actions">
          <button class="action-icon-btn qr-btn" onclick="showQRCode('${qrCodeUrl}', '${report.riverName}')" title="View QR Code">
            <i class="fas fa-qrcode"></i>
          </button>
          <button class="action-icon-btn" onclick="shareReport(${report.id})" title="Share Report">
            <i class="fas fa-share-nodes"></i>
          </button>
          <button class="action-icon-btn" onclick="viewFullReport(${report.id})" title="View Full Report">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>
    </div>
  `;
  
  // Click on card to view full report
  card.addEventListener('click', (e) => {
    // Don't trigger if clicking action buttons
    if (!e.target.closest('.action-icon-btn')) {
      viewFullReport(report.id);
    }
  });
  
  return card;
}

/**
 * Format pollution type for display
 * @param {String} type - Pollution type key
 * @returns {String} - Formatted pollution type
 */
function formatPollutionType(type) {
  const types = {
    'plastic': 'Plastic Waste',
    'chemical': 'Chemical Discharge',
    'sewage': 'Sewage & Domestic',
    'industrial': 'Industrial Waste',
    'other': 'Other Pollutants'
  };
  return types[type] || type;
}

// ===== 5. SEARCH & FILTER FUNCTIONALITY =====

/**
 * Initialize all event listeners
 */
function initializeEventListeners() {
  // Search input
  const searchInput = document.getElementById('searchInput');
  searchInput.addEventListener('input', debounce(handleSearch, 300));
  
  // Filter buttons
  const filterButtons = document.querySelectorAll('.filter-btn');
  filterButtons.forEach(btn => {
    btn.addEventListener('click', handleFilterClick);
  });
  
  // Pollution type dropdown
  const pollutionFilter = document.getElementById('pollutionTypeFilter');
  pollutionFilter.addEventListener('change', applyFilters);
  
  // Back to top button
  const backToTop = document.getElementById('backToTop');
  backToTop.addEventListener('click', scrollToTop);
  
  // Scroll event for back to top visibility
  window.addEventListener('scroll', toggleBackToTop);
  
  // Close modal on overlay click
  const modalOverlay = document.getElementById('reportDetailModal');
  modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
      closeReportModal();
    }
  });
  
  // Close modal on ESC key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeReportModal();
    }
  });
}

/**
 * Handle search input with fade animation
 */
function handleSearch(e) {
  const searchTerm = e.target.value.toLowerCase().trim();
  applyFilters();
}

/**
 * Handle filter button clicks
 * @param {Event} e - Click event
 */
function handleFilterClick(e) {
  const btn = e.currentTarget;
  
  // Remove active class from all buttons
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  
  // Add active class to clicked button
  btn.classList.add('active');
  
  // Apply filters
  applyFilters();
}

/**
 * Apply all active filters (search + status + pollution type)
 * Uses smooth fade animation when filtering
 */
function applyFilters() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
  const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
  const pollutionType = document.getElementById('pollutionTypeFilter').value;
  
  // Fade out current cards
  const reportsGrid = document.getElementById('reportsGrid');
  reportsGrid.style.opacity = '0';
  reportsGrid.style.transform = 'translateY(20px)';
  
  setTimeout(() => {
    // Filter reports
    filteredReports = allReports.filter(report => {
      // Search filter
      const matchesSearch = searchTerm === '' || 
        report.riverName.toLowerCase().includes(searchTerm) ||
        report.location.toLowerCase().includes(searchTerm) ||
        report.description.toLowerCase().includes(searchTerm) ||
        formatPollutionType(report.pollutionType).toLowerCase().includes(searchTerm);
      
      // Status filter (Pending / Verified / Resolved)
      const matchesStatus = activeFilter === 'all' || report.status.toLowerCase() === activeFilter.toLowerCase();
      
      // Pollution type filter (case-insensitive)
      const matchesPollution = pollutionType === 'all' || 
        report.pollutionType.toLowerCase() === pollutionType.toLowerCase();
      
      return matchesSearch && matchesStatus && matchesPollution;
    });
    
    // Sort reports: Primary by pollution type, Secondary by status (Pending → Verified → Resolved)
    const pollutionTypeOrder = {
      'Chemical': 1,
      'Industrial': 2,
      'Sewage': 3,
      'Agricultural': 4,
      'Plastic': 5,
      'Other': 6
    };
    
    const statusOrder = {
      'pending': 1,
      'verified': 2,
      'resolved': 3
    };
    
    filteredReports.sort((a, b) => {
      // Primary sort: pollution type
      const pollutionA = pollutionTypeOrder[a.pollutionType] || 999;
      const pollutionB = pollutionTypeOrder[b.pollutionType] || 999;
      
      if (pollutionA !== pollutionB) {
        return pollutionA - pollutionB;
      }
      
      // Secondary sort: status (Pending → Verified → Resolved)
      const statusA = statusOrder[a.status] || 999;
      const statusB = statusOrder[b.status] || 999;
      
      return statusA - statusB;
    });
    
    // Render filtered reports
    renderReports(filteredReports);
    
    // Fade in new cards
    setTimeout(() => {
      reportsGrid.style.opacity = '1';
      reportsGrid.style.transform = 'translateY(0)';
    }, 50);
  }, 300);
}

/**
 * Debounce function to limit search execution
 * @param {Function} func - Function to debounce
 * @param {Number} wait - Wait time in milliseconds
 */
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// ===== 6. MODAL FUNCTIONALITY =====

/**
 * View full report in modal popup
 * @param {Number} reportId - Report ID to display
 */
function viewFullReport(reportId) {
  const report = allReports.find(r => r.id === reportId);
  if (!report) return;
  
  const modal = document.getElementById('reportDetailModal');
  const modalContent = document.getElementById('modalContent');
  
  // Generate QR code URL
  const reportUrl = `${window.location.origin}/Webby/dashboard.php?id=${reportId}`;
  const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(reportUrl)}&size=200x200`;
  
  // Build modal content (reuse enhanced modal HTML from reports-enhanced.js)
  modalContent.innerHTML = `
    <div class="modal-body" style="padding: 40px;">
      <h2 style="font-size: 2rem; margin-bottom: 10px; background: linear-gradient(135deg, #0096c7, #00b4d8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        ${report.riverName} River
      </h2>
      <p style="color: var(--text-secondary); margin-bottom: 30px;">${report.location}</p>
      
      <!-- Report Photo -->
      <img src="${report.image}" alt="${report.riverName}" 
           style="width: 100%; height: 350px; object-fit: cover; border-radius: 16px; margin-bottom: 30px;">
      
      <!-- Status & Info Grid -->
      <div class="detail-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="detail-item">
          <div class="detail-label" style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 8px;">Status</div>
          <div class="detail-value">
            <span class="report-status-badge status-${report.status}">${report.status}</span>
          </div>
        </div>
        <div class="detail-item">
          <div class="detail-label" style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 8px;">Pollution Type</div>
          <div class="detail-value" style="color: var(--text-primary);">${formatPollutionType(report.pollutionType)}</div>
        </div>
        <div class="detail-item">
          <div class="detail-label" style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 8px;">Date Submitted</div>
          <div class="detail-value" style="color: var(--text-primary);">${report.dateSubmitted}</div>
        </div>
        <div class="detail-item">
          <div class="detail-label" style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 8px;">Reported By</div>
          <div class="detail-value" style="color: var(--text-primary);">${report.reportedBy}</div>
        </div>
      </div>
      
      <!-- Description -->
      <div style="margin-bottom: 30px;">
        <h3 style="color: #0096c7; margin-bottom: 15px; font-size: 1.3rem;">Description</h3>
        <p style="color: var(--text-secondary); line-height: 1.8;">${report.description}</p>
      </div>
      
      <!-- Coordinates -->
      <div style="margin-bottom: 30px;">
        <h3 style="color: #0096c7; margin-bottom: 15px; font-size: 1.3rem;">Location Coordinates</h3>
        <p style="color: var(--text-secondary); font-family: monospace; font-size: 1.1rem;">${report.coordinates}</p>
      </div>
      
      <!-- Authority Response -->
      ${report.authorityResponse ? `
        <div style="margin-bottom: 30px;">
          <h3 style="color: #0096c7; margin-bottom: 15px; font-size: 1.3rem;">Authority Response</h3>
          <div style="background: rgba(0, 150, 199, 0.1); padding: 20px; border-radius: 12px; border-left: 4px solid #0096c7;">
            <p style="color: var(--text-primary); line-height: 1.8;">${report.authorityResponse}</p>
          </div>
        </div>
      ` : ''}
      
      <!-- QR Code Section -->
      <div class="modal-qr-section">
        <h3 style="color: #0096c7; margin-bottom: 20px; font-size: 1.3rem;">Scan to View Report</h3>
        <img src="${qrCodeUrl}" alt="QR Code" title="📱 Scan to view this report">
        <p>Scan this QR code to view the report on your mobile device</p>
      </div>
      
      <!-- Share Buttons -->
      <div style="display: flex; gap: 15px; justify-content: center; margin-top: 40px; padding-top: 30px; border-top: 2px solid rgba(0, 180, 216, 0.15); flex-wrap: wrap;">
        <button onclick="shareReport(${reportId})" class="action-button primary-action">
          <i class="fas fa-share-nodes"></i> Share Report
        </button>
        <button onclick="copyReportLink('${reportUrl}')" class="action-button secondary-action">
          <i class="fas fa-copy"></i> Copy Link
        </button>
      </div>
    </div>
  `;
  
  // Show modal with animation
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

/**
 * Close report detail modal
 */
function closeReportModal() {
  const modal = document.getElementById('reportDetailModal');
  modal.classList.remove('active');
  document.body.style.overflow = 'auto';
}

// ===== 7. QR CODE FUNCTIONALITY =====

/**
 * Show QR code in a quick modal
 * @param {String} qrCodeUrl - QR code image URL
 * @param {String} riverName - River name for title
 */
function showQRCode(qrCodeUrl, riverName) {
  const modal = document.getElementById('qrModal');
  const qrContainer = document.getElementById('qrCodeContainer');
  
  // Inject QR code image
  qrContainer.innerHTML = `<img src="${qrCodeUrl}" alt="QR Code for ${riverName}" class="qr-image">`;
  
  // Set up download button
  const downloadBtn = document.getElementById('downloadQRBtn');
  downloadBtn.onclick = function() {
    const link = document.createElement('a');
    link.href = qrCodeUrl;
    link.download = `RiverVibe-${riverName}-Report-QR.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showNotification('QR Code downloaded! 🎉', 'success');
  };
  
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

/**
 * Close QR code modal
 */
function closeQRModal() {
  const modal = document.getElementById('qrModal');
  modal.classList.remove('active');
  document.body.style.overflow = 'auto';
}




/**
 * Show notification toast
 * @param {String} message - Message to display
 * @param {String} type - Type (success, error, info)
 */
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: ${type === 'success' ? '#16a34a' : type === 'error' ? '#dc2626' : '#0096c7'};
    color: white;
    padding: 16px 24px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    z-index: 10002;
    font-family: 'Poppins', sans-serif;
    font-size: 0.95rem;
    animation: slideInRight 0.3s ease;
  `;
  notification.textContent = message;
  
  document.body.appendChild(notification);
  
  setTimeout(() => {
    notification.style.animation = 'slideOutRight 0.3s ease';
    setTimeout(() => notification.remove(), 300);
  }, 3000);
}

// ===== 9. SCROLL ANIMATIONS =====

/**
 * Initialize IntersectionObserver for scroll animations
 */
function initializeScrollAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, observerOptions);
  
  // Observe filter section
  const filterSection = document.querySelector('.filter-controls');
  if (filterSection) {
    observer.observe(filterSection);
  }
}

/**
 * Observe report cards for scroll animations
 */
function observeCards() {
  const cards = document.querySelectorAll('.report-card');
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, {
    threshold: 0.1
  });
  
  cards.forEach(card => observer.observe(card));
}

// ===== 10. NUMBER COUNTER ANIMATION =====

/**
 * Animate stat numbers counting up
 */
function animateCounters() {
  const counters = document.querySelectorAll('.stat-number');
  
  counters.forEach(counter => {
    const target = parseInt(counter.dataset.target);
    const duration = 2000; // 2 seconds
    const increment = target / (duration / 16); // 60fps
    let current = 0;
    
    const updateCounter = () => {
      current += increment;
      if (current < target) {
        counter.textContent = Math.floor(current);
        requestAnimationFrame(updateCounter);
      } else {
        counter.textContent = target;
      }
    };
    
    // Start animation when visible
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          updateCounter();
          observer.unobserve(entry.target);
        }
      });
    });
    
    observer.observe(counter);
  });
}

// ===== 11. BACK TO TOP BUTTON =====

/**
 * Toggle back to top button visibility based on scroll
 */
function toggleBackToTop() {
  const backToTop = document.getElementById('backToTop');
  if (window.scrollY > 300) {
    backToTop.classList.add('visible');
  } else {
    backToTop.classList.remove('visible');
  }
}

/**
 * Scroll to top smoothly
 */
function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

// ===== 12. SHARE REPORT FUNCTIONALITY =====

/**
 * Main share report function - opens share menu with report-specific URL
 * @param {Number} reportId - ID of the report to share
 */
function shareReport(reportId) {
  const report = allReports.find(r => r.id === reportId);
  if (!report) return;
  
  const reportUrl = `${window.location.origin}/Webby/dashboard.php?id=${reportId}`;
  const shareText = `Check out this pollution report: ${report.riverName} River - RiverVibe 🌊`;
  
  showShareMenu(reportUrl, shareText);
}

/**
 * Show minimal, responsive share menu
 * @param {String} url - URL to share
 * @param {String} text - Share text/message
 */
function showShareMenu(url, text = 'Check out this report on RiverVibe') {
  // Remove existing share menu if any
  const existingMenu = document.getElementById('shareMenu');
  if (existingMenu) existingMenu.remove();
  
  const existingBackdrop = document.getElementById('shareMenuBackdrop');
  if (existingBackdrop) existingBackdrop.remove();
  
  // Create backdrop
  const backdrop = document.createElement('div');
  backdrop.id = 'shareMenuBackdrop';
  backdrop.className = 'share-menu-backdrop';
  backdrop.onclick = closeShareMenu;
  
  // Create share menu
  const shareMenu = document.createElement('div');
  shareMenu.id = 'shareMenu';
  shareMenu.className = 'share-menu';
  
  shareMenu.innerHTML = `
    <div class="share-menu-header">
      <h3><i class="fas fa-share-nodes"></i> Share Report</h3>
      <button class="share-close-btn" onclick="closeShareMenu()" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <div class="share-menu-body">
      <button class="share-btn share-whatsapp" onclick="shareToWhatsApp('${encodeURIComponent(text)}', '${encodeURIComponent(url)}')">
        <i class="fab fa-whatsapp"></i>
        <span>WhatsApp</span>
      </button>
      
      <button class="share-btn share-twitter" onclick="shareToTwitter('${encodeURIComponent(text)}', '${encodeURIComponent(url)}')">
        <i class="fab fa-twitter"></i>
        <span>Twitter / X</span>
      </button>
      
      <button class="share-btn share-instagram" onclick="shareToInstagram('${encodeURIComponent(url)}')">
        <i class="fab fa-instagram"></i>
        <span>Instagram</span>
      </button>
      
      <button class="share-btn share-copy" onclick="copyShareLink('${url}')">
        <i class="fas fa-link"></i>
        <span>Copy Link</span>
      </button>
    </div>
  `;
  
  // Append to body
  document.body.appendChild(backdrop);
  document.body.appendChild(shareMenu);
  
  // Trigger animation
  setTimeout(() => {
    backdrop.classList.add('active');
    shareMenu.classList.add('active');
  }, 10);
  
  // Inject CSS if not already present
  injectShareMenuStyles();
}

/**
 * Close share menu
 */
function closeShareMenu() {
  const shareMenu = document.getElementById('shareMenu');
  const backdrop = document.getElementById('shareMenuBackdrop');
  
  if (shareMenu) {
    shareMenu.classList.remove('active');
    setTimeout(() => shareMenu.remove(), 300);
  }
  
  if (backdrop) {
    backdrop.classList.remove('active');
    setTimeout(() => backdrop.remove(), 300);
  }
}

/**
 * Share to WhatsApp
 */
function shareToWhatsApp(text, url) {
  const message = `${decodeURIComponent(text)} ${decodeURIComponent(url)}`;
  window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
  closeShareMenu();
}

/**
 * Share to Twitter
 */
function shareToTwitter(text, url) {
  window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
  closeShareMenu();
}

/**
 * Share to Instagram
 */
function shareToInstagram(url) {
  const decodedUrl = decodeURIComponent(url);
  const isMobile = /Android|webOS|iPhone|iPad|iPod/i.test(navigator.userAgent);
  
  if (isMobile) {
    // Try to open Instagram app
    window.location.href = `instagram://share?text=${encodeURIComponent(decodedUrl)}`;
    
    // Fallback: Copy to clipboard and notify
    setTimeout(() => {
      copyShareLink(decodedUrl);
      showNotification('Link copied! Paste in Instagram app', 'info');
    }, 1000);
  } else {
    // Desktop fallback
    copyShareLink(decodedUrl);
    closeShareMenu();
    showNotification('Instagram sharing works only on mobile. Link copied!', 'info');
  }
}

/**
 * Copy link to clipboard
 */
function copyShareLink(url) {
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(url).then(() => {
      showNotification('✓ Link copied to clipboard!', 'success');
      closeShareMenu();
    }).catch(err => {
      console.error('Clipboard error:', err);
      fallbackCopyLink(url);
    });
  } else {
    fallbackCopyLink(url);
  }
}

/**
 * Fallback copy method for older browsers
 */
function fallbackCopyLink(url) {
  const textArea = document.createElement('textarea');
  textArea.value = url;
  textArea.style.cssText = 'position: fixed; top: -9999px; left: -9999px;';
  document.body.appendChild(textArea);
  textArea.select();
  
  try {
    document.execCommand('copy');
    showNotification('✓ Link copied to clipboard!', 'success');
    closeShareMenu();
  } catch (err) {
    console.error('Fallback copy failed:', err);
    showNotification('Failed to copy link', 'error');
  } finally {
    document.body.removeChild(textArea);
  }
}

/**
 * Inject share menu CSS styles (only once)
 */
function injectShareMenuStyles() {
  if (document.getElementById('shareMenuStyles')) return;
  
  const styles = document.createElement('style');
  styles.id = 'shareMenuStyles';
  styles.textContent = `
    /* Share Menu Backdrop */
    .share-menu-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      z-index: 10000;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .share-menu-backdrop.active {
      opacity: 1;
    }
    
    /* Share Menu Container */
    .share-menu {
      position: fixed;
      bottom: -400px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--bg-primary, #ffffff);
      border-radius: 16px 16px 0 0;
      box-shadow: 0 -4px 24px rgba(0, 0, 0, 0.15);
      z-index: 10001;
      width: 90%;
      max-width: 420px;
      transition: bottom 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .share-menu.active {
      bottom: 0;
    }
    
    /* Desktop: Center modal */
    @media (min-width: 768px) {
      .share-menu {
        bottom: auto;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        border-radius: 16px;
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      
      .share-menu.active {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
      }
    }
    
    /* Share Menu Header */
    .share-menu-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 24px;
      border-bottom: 1px solid var(--border-color, rgba(0, 0, 0, 0.1));
    }
    
    .share-menu-header h3 {
      margin: 0;
      font-size: 1.2rem;
      font-weight: 600;
      color: var(--text-primary, #1a1a1a);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .share-menu-header h3 i {
      color: #0096c7;
    }
    
    .share-close-btn {
      background: none;
      border: none;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: var(--text-secondary, #666);
      font-size: 1.3rem;
      transition: all 0.3s ease;
    }
    
    .share-close-btn:hover {
      background: rgba(220, 38, 38, 0.1);
      color: #dc2626;
      transform: rotate(90deg);
    }
    
    /* Share Menu Body */
    .share-menu-body {
      padding: 20px 24px 28px;
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }
    
    /* Share Buttons */
    .share-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 20px 16px;
      border: 2px solid var(--border-color, rgba(0, 0, 0, 0.1));
      border-radius: 12px;
      background: var(--bg-secondary, #f8f9fa);
      color: var(--text-primary, #1a1a1a);
      font-family: 'Poppins', sans-serif;
      font-size: 0.9rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .share-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0, 150, 199, 0.2);
      border-color: #0096c7;
    }
    
    .share-btn i {
      font-size: 1.8rem;
      transition: transform 0.3s ease;
    }
    
    .share-btn:hover i {
      transform: scale(1.15);
    }
    
    /* Platform-specific colors */
    .share-whatsapp:hover {
      border-color: #25d366;
      box-shadow: 0 6px 20px rgba(37, 211, 102, 0.25);
    }
    
    .share-whatsapp i {
      color: #25d366;
    }
    
    .share-twitter:hover {
      border-color: #1da1f2;
      box-shadow: 0 6px 20px rgba(29, 161, 242, 0.25);
    }
    
    .share-twitter i {
      color: #1da1f2;
    }
    
    .share-instagram:hover {
      border-color: #e4405f;
      box-shadow: 0 6px 20px rgba(228, 64, 95, 0.25);
    }
    
    .share-instagram i {
      background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .share-copy:hover {
      border-color: #0096c7;
      box-shadow: 0 6px 20px rgba(0, 150, 199, 0.25);
    }
    
    .share-copy i {
      color: #0096c7;
    }
    
    /* Dark Mode Support */
    body.dark-mode .share-menu,
    [data-theme="dark"] .share-menu {
      background: #1a1a1a;
      box-shadow: 0 -4px 24px rgba(0, 0, 0, 0.6);
    }
    
    body.dark-mode .share-menu-header,
    [data-theme="dark"] .share-menu-header {
      border-bottom-color: rgba(255, 255, 255, 0.1);
    }
    
    body.dark-mode .share-menu-header h3,
    [data-theme="dark"] .share-menu-header h3 {
      color: #f5f5f5;
    }
    
    body.dark-mode .share-btn,
    [data-theme="dark"] .share-btn {
      background: rgba(255, 255, 255, 0.05);
      border-color: rgba(255, 255, 255, 0.1);
      color: #f5f5f5;
    }
    
    body.dark-mode .share-btn:hover,
    [data-theme="dark"] .share-btn:hover {
      background: rgba(255, 255, 255, 0.08);
    }
    
    /* Mobile: Full width buttons on small screens */
    @media (max-width: 480px) {
      .share-menu-body {
        grid-template-columns: 1fr;
      }
      
      .share-btn {
        flex-direction: row;
        justify-content: flex-start;
        padding: 16px 20px;
      }
      
      .share-btn i {
        font-size: 1.5rem;
      }
    }
  `;
  
  document.head.appendChild(styles);
}

// Close share menu on ESC key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const shareMenu = document.getElementById('shareMenu');
    if (shareMenu && shareMenu.classList.contains('active')) {
      closeShareMenu();
    }
  }
});


// ===== 13. LEGACY DESKTOP SHARE MENU (Deprecated - keeping for compatibility) =====

/**
 * Show desktop share menu (fallback for Web Share API)
 * Reuses functionality from reports-enhanced.js
 */
function showDesktopShareMenu(shareData) {
  // Create share menu if it doesn't exist
  let shareMenu = document.getElementById('customShareMenu');
  
  if (!shareMenu) {
    shareMenu = document.createElement('div');
    shareMenu.id = 'customShareMenu';
    shareMenu.style.cssText = `
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: var(--bg-primary, white);
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.2);
      z-index: 10001;
      min-width: 320px;
    `;
    
    shareMenu.innerHTML = `
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: var(--text-primary, #1a1a1a); font-size: 1.3rem;">Share Report</h3>
        <button onclick="closeDesktopShareMenu()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-secondary, #666);">&times;</button>
      </div>
      <div id="shareButtons"></div>
    `;
    
    document.body.appendChild(shareMenu);
  }
  
  // Update share buttons
  const shareButtons = shareMenu.querySelector('#shareButtons');
  shareButtons.innerHTML = `
    <button onclick="shareToWhatsApp('${shareData.text.replace(/'/g, "\\'")}', '${shareData.url}')" class="share-platform-btn">
      <i class="fab fa-whatsapp" style="color: #25d366;"></i>
      <span>WhatsApp</span>
    </button>
    <button onclick="shareToFacebook('${shareData.url}')" class="share-platform-btn">
      <i class="fab fa-facebook" style="color: #1877f2;"></i>
      <span>Facebook</span>
    </button>
    <button onclick="shareToTwitter('${shareData.text.replace(/'/g, "\\'")}', '${shareData.url}')" class="share-platform-btn">
      <i class="fab fa-twitter" style="color: #1da1f2;"></i>
      <span>Twitter</span>
    </button>
    <button onclick="copyReportLink('${shareData.url}')" class="share-platform-btn">
      <i class="fas fa-copy" style="color: #0096c7;"></i>
      <span>Copy Link</span>
    </button>
  `;
  
  shareMenu.style.display = 'block';
  
  // Add backdrop
  let backdrop = document.getElementById('shareBackdrop');
  if (!backdrop) {
    backdrop = document.createElement('div');
    backdrop.id = 'shareBackdrop';
    backdrop.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(5px);
      z-index: 10000;
    `;
    backdrop.onclick = closeDesktopShareMenu;
    document.body.insertBefore(backdrop, shareMenu);
  }
  backdrop.style.display = 'block';
}

function closeDesktopShareMenu() {
  const shareMenu = document.getElementById('customShareMenu');
  const backdrop = document.getElementById('shareBackdrop');
  if (shareMenu) shareMenu.style.display = 'none';
  if (backdrop) backdrop.style.display = 'none';
}

function shareToWhatsApp(text, url) {
  window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(text + ' ' + url)}`, '_blank');
  closeDesktopShareMenu();
}

function shareToFacebook(url) {
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
  closeDesktopShareMenu();
}

function shareToTwitter(text, url) {
  window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`, '_blank');
  closeDesktopShareMenu();
}

// ===== CLICK OUTSIDE TO CLOSE MODALS =====
window.addEventListener('click', function(e) {
  // Close report detail modal when clicking on overlay
  const reportModal = document.getElementById('reportDetailModal');
  if (e.target === reportModal) {
    closeReportModal();
  }
  
  // Close QR modal when clicking on overlay
  const qrModal = document.getElementById('qrModal');
  if (e.target === qrModal) {
    closeQRModal();
  }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeReportModal();
    closeQRModal();
  }
});

console.log('🌊 RiverVibe Reports Dynamic JS - Loaded Successfully!');
