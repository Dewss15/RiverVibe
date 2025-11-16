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
const mockReportsData = [
  {
    id: 1,
    riverName: 'Ganga',
    location: 'Varanasi Ghat Area, Uttar Pradesh',
    pollutionType: 'chemical',
    description: 'Multiple instances of chemical discharge observed near the industrial area. Water shows visible discoloration and strong chemical odor. Local wildlife affected. Immediate attention required.',
    photo: 'https://images.unsplash.com/photo-1599398054066-846f28917f38?w=600',
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
    photo: 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?w=600',
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
    photo: 'https://images.unsplash.com/photo-1621451537084-482c73073a0f?w=600',
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
    photo: 'https://images.unsplash.com/photo-1583224964697-f5c2c8b1d11c?w=600',
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
    photo: 'https://images.unsplash.com/photo-1547036967-23d11aacaee0?w=600',
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
    photo: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=600',
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
    photo: 'https://images.unsplash.com/photo-1590845947670-c009801ffa74?w=600',
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
    photo: 'https://images.unsplash.com/photo-1586348943529-beaae6c28db9?w=600',
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

// ===== 2. INITIALIZATION =====

/**
 * Initialize the reports page when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', () => {
  console.log('🌊 RiverVibe Reports - Initializing...');
  
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

// ===== 3. DATA FETCHING =====

/**
 * Fetch reports from backend or use mock data
 * In production: Replace with actual API call
 */
async function fetchReports() {
  const loadingSpinner = document.getElementById('loadingSpinner');
  const reportsGrid = document.getElementById('reportsGrid');
  
  try {
    // Show loading spinner
    loadingSpinner.style.display = 'flex';
    
    // Simulate API delay (remove in production)
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    // In production, replace with:
    // const response = await fetch('get_reports.php');
    // allReports = await response.json();
    
    allReports = mockReportsData;
    filteredReports = [...allReports];
    
    // Hide loading spinner
    loadingSpinner.style.display = 'none';
    
    // Render reports
    renderReports(filteredReports);
    
  } catch (error) {
    console.error('Error fetching reports:', error);
    loadingSpinner.innerHTML = `
      <div style="text-align: center; color: #dc2626;">
        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 20px;"></i>
        <p>Failed to load reports. Please try again later.</p>
      </div>
    `;
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
  card.className = 'report-card';
  card.style.animationDelay = `${index * 0.1}s`;
  card.dataset.reportId = report.id;
  card.dataset.status = report.status;
  card.dataset.pollutionType = report.pollutionType;
  
  // Status badge class
  const statusClass = `status-${report.status}`;
  
  // Generate QR code URL
  const reportUrl = `${window.location.origin}/reports.html?id=${report.id}`;
  const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(reportUrl)}&size=120x120`;
  
  card.innerHTML = `
    <img src="${report.photo}" alt="${report.riverName} River" class="report-card-image" loading="lazy">
    <div class="report-card-content">
      <div class="report-card-header">
        <h3 class="report-river-name">${report.riverName}</h3>
        <span class="report-status-badge ${statusClass}">${report.status}</span>
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
  const activeStatus = document.querySelector('.filter-btn.active').dataset.filter;
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
      
      // Status filter
      const matchesStatus = activeStatus === 'all' || report.status === activeStatus;
      
      // Pollution type filter
      const matchesPollution = pollutionType === 'all' || report.pollutionType === pollutionType;
      
      return matchesSearch && matchesStatus && matchesPollution;
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
  const reportUrl = `${window.location.origin}/reports.html?id=${reportId}`;
  const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(reportUrl)}&size=200x200`;
  
  // Build modal content (reuse enhanced modal HTML from reports-enhanced.js)
  modalContent.innerHTML = `
    <div class="modal-body" style="padding: 40px;">
      <h2 style="font-size: 2rem; margin-bottom: 10px; background: linear-gradient(135deg, #0096c7, #00b4d8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        ${report.riverName} River
      </h2>
      <p style="color: var(--text-secondary); margin-bottom: 30px;">${report.location}</p>
      
      <!-- Report Photo -->
      <img src="${report.photo}" alt="${report.riverName}" 
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
      <div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px; flex-wrap: wrap;">
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
  const modal = document.getElementById('reportDetailModal');
  const modalContent = document.getElementById('modalContent');
  
  modalContent.innerHTML = `
    <div style="padding: 60px 40px; text-align: center;">
      <h2 style="font-size: 2rem; margin-bottom: 30px; background: linear-gradient(135deg, #0096c7, #00b4d8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        ${riverName} River - QR Code
      </h2>
      <div class="modal-qr-section">
        <img src="${qrCodeUrl}" alt="QR Code" style="width: 250px; height: 250px;">
        <p style="margin-top: 20px; font-size: 1.1rem;">📱 Scan to view this report on your mobile device</p>
      </div>
    </div>
  `;
  
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

// ===== 8. SHARE FUNCTIONALITY =====

/**
 * Share report using Web Share API or fallback
 * @param {Number} reportId - Report ID to share
 */
async function shareReport(reportId) {
  const report = allReports.find(r => r.id === reportId);
  if (!report) return;
  
  const shareData = {
    title: `RiverVibe Report: ${report.riverName} River`,
    text: `Check out this pollution report on RiverVibe 🌊 - ${report.riverName} River`,
    url: `${window.location.origin}/reports.html?id=${reportId}`
  };
  
  // Check if Web Share API is supported
  if (navigator.share && /Android|webOS|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
    try {
      await navigator.share(shareData);
      showNotification('Report shared successfully!', 'success');
    } catch (error) {
      if (error.name !== 'AbortError') {
        console.log('Share cancelled or failed:', error);
      }
    }
  } else {
    // Fallback to custom share menu (reuse from reports-enhanced.js)
    showDesktopShareMenu(shareData);
  }
}

/**
 * Copy report link to clipboard
 * @param {String} url - URL to copy
 */
async function copyReportLink(url) {
  try {
    await navigator.clipboard.writeText(url);
    showNotification('✓ Link copied to clipboard!', 'success');
  } catch (error) {
    // Fallback for older browsers
    const textArea = document.createElement('textarea');
    textArea.value = url;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    showNotification('✓ Link copied to clipboard!', 'success');
  }
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

// ===== 12. UTILITY: DESKTOP SHARE MENU =====

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

console.log('🌊 RiverVibe Reports Dynamic JS - Loaded Successfully!');
