/********************************************************************************************
 * 🌊 RIVERVIBE REPORTS PAGE ENHANCEMENT - Interactive Features
 * -------------------------------------------------------------------------------------------
 * Features:
 * 1. View Full Report Modal with dynamic content loading
 * 2. Share Report with Web Share API and social platform links
 * 3. QR Code Generator for each report
 * 4. Theme-aware styling (Light/Dark mode support)
 ********************************************************************************************/

// ===== 1. VIEW FULL REPORT MODAL FUNCTIONALITY =====

/**
 * Opens a modal with full report details
 * Fetches report data and displays it in a modal popup
 * @param {string} riverId - The ID of the river report to display
 */
async function openFullReportModal(riverId) {
  const modal = document.getElementById('reportModal');
  const modalTitle = document.getElementById('modalTitle');
  const modalBody = document.getElementById('modalBody');
  
  // Show loading state
  modalTitle.textContent = 'Loading Report...';
  modalBody.innerHTML = '<div style="text-align: center; padding: 40px;"><i class="fas fa-spinner fa-spin" style="font-size: 3rem; color: #0096c7;"></i></div>';
  
  // Open modal with fade-in animation
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
  
  try {
    // Fetch report data from server (or use local data if server not available)
    const reportData = await fetchReportData(riverId);
    
    // Update modal content
    modalTitle.textContent = `Full Report: ${reportData.name} River`;
    modalBody.innerHTML = generateFullReportHTML(reportData);
    
  } catch (error) {
    console.error('Error loading report:', error);
    modalBody.innerHTML = `
      <div style="text-align: center; padding: 40px; color: #dc2626;">
        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 20px;"></i>
        <p style="font-size: 1.2rem;">Failed to load report data</p>
        <p style="color: #64748b;">Please try again later</p>
      </div>
    `;
  }
}

/**
 * Fetches report data from server or returns mock data
 * In production, this would call get_report.php via Fetch API
 * @param {string} riverId - The river identifier
 * @returns {Promise<Object>} Report data object
 */
async function fetchReportData(riverId) {
  // Simulate API call delay
  await new Promise(resolve => setTimeout(resolve, 500));
  
  // Mock data - In production, replace with:
  // const response = await fetch(`get_report.php?river=${riverId}`);
  // return await response.json();
  
  const reportDatabase = {
    ganga: {
      name: 'Ganga',
      location: 'Varanasi Ghat Area, Uttar Pradesh',
      pollutionType: 'Industrial & Chemical Waste',
      description: 'Multiple instances of chemical discharge observed near the industrial area. Water shows visible discoloration and strong chemical odor. Local wildlife affected. Immediate attention required.',
      photo: 'https://images.unsplash.com/photo-1599398054066-846f28917f38?w=600',
      status: 'Verified',
      dateSubmitted: 'October 20, 2025',
      reportedBy: 'Citizens Group',
      authorityResponse: 'Municipal authorities have been notified. Investigation team deployed to assess the situation. Cleanup operations scheduled for next week.',
      coordinates: '25.3176° N, 82.9739° E',
      severity: 'Critical',
      waterQuality: {
        ph: 8.2,
        dissolvedOxygen: 2.1,
        bod: 12.5,
        turbidity: 45
      }
    },
    yamuna: {
      name: 'Yamuna',
      location: 'Okhla Barrage, Delhi',
      pollutionType: 'Sewage & Domestic Waste',
      description: 'Heavy sewage discharge continues to pollute the river. Foam formation and black water observed. Urgent intervention needed to address untreated sewage disposal.',
      photo: 'https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?w=600',
      status: 'Pending',
      dateSubmitted: 'October 20, 2025',
      reportedBy: 'Environmental NGO',
      authorityResponse: 'Report under review by Delhi Pollution Control Committee.',
      coordinates: '28.5244° N, 77.3109° E',
      severity: 'Critical',
      waterQuality: {
        ph: 7.8,
        dissolvedOxygen: 1.5,
        bod: 18.3,
        turbidity: 62
      }
    },
    mithi: {
      name: 'Mithi',
      location: 'Kurla-BKC Area, Mumbai',
      pollutionType: 'Plastic & Municipal Waste',
      description: 'Significant accumulation of plastic waste and debris observed. Local clean-up initiatives ongoing but requiring more support.',
      photo: 'https://images.unsplash.com/photo-1621451537084-482c73073a0f?w=600',
      status: 'Resolved',
      dateSubmitted: 'October 18, 2025',
      reportedBy: 'Local Resident',
      authorityResponse: 'Cleanup drive completed successfully. Regular monitoring established.',
      coordinates: '19.0728° N, 72.8826° E',
      severity: 'Medium',
      waterQuality: {
        ph: 7.2,
        dissolvedOxygen: 4.8,
        bod: 8.5,
        turbidity: 28
      }
    }
  };
  
  // Return data for requested river or default to Ganga
  return reportDatabase[riverId] || reportDatabase.ganga;
}

/**
 * Generates HTML content for the full report modal
 * @param {Object} data - Report data object
 * @returns {string} HTML string for modal body
 */
function generateFullReportHTML(data) {
  const reportUrl = `${window.location.origin}/Webby/dashboard.php?id=${data.id || data.name.toLowerCase()}`;
  const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(reportUrl)}&size=150x150`;
  
  // Status badge color
  const statusColors = {
    'Verified': 'background: #dcfce7; color: #16a34a;',
    'Pending': 'background: #fef3c7; color: #d97706;',
    'Resolved': 'background: #dbeafe; color: #0284c7;'
  };
  
  return `
    <!-- Report Photo -->
    <div class="detail-section">
      <img src="${data.photo}" alt="${data.name} River" 
           style="width: 100%; height: 300px; object-fit: cover; border-radius: 12px; margin-bottom: 20px;">
    </div>
    
    <!-- Report Status & Key Info -->
    <div class="detail-section">
      <h3>Report Status</h3>
      <div class="detail-grid">
        <div class="detail-item">
          <div class="detail-label">Current Status</div>
          <div class="detail-value">
            <span style="display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 0.9rem; ${statusColors[data.status]}">
              ${data.status}
            </span>
          </div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Date Submitted</div>
          <div class="detail-value">${data.dateSubmitted}</div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Reported By</div>
          <div class="detail-value">${data.reportedBy}</div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Severity Level</div>
          <div class="detail-value severity-${data.severity.toLowerCase()}">${data.severity}</div>
        </div>
      </div>
    </div>
    
    <!-- Location & Pollution Details -->
    <div class="detail-section">
      <h3>Location & Pollution Details</h3>
      <div class="detail-grid">
        <div class="detail-item">
          <div class="detail-label">Location</div>
          <div class="detail-value">${data.location}</div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Coordinates</div>
          <div class="detail-value">${data.coordinates}</div>
        </div>
        <div class="detail-item">
          <div class="detail-label">Pollution Type</div>
          <div class="detail-value">${data.pollutionType}</div>
        </div>
      </div>
      <div class="detail-item" style="margin-top: 20px;">
        <div class="detail-label">Description</div>
        <div class="detail-value" style="line-height: 1.8;">${data.description}</div>
      </div>
    </div>
    
    <!-- Water Quality Metrics -->
    <div class="detail-section">
      <h3>Water Quality Analysis</h3>
      <div class="gauge-grid">
        <div class="gauge-chart">
          <div class="gauge-radial" style="--p: ${Math.min(data.waterQuality.ph / 14 * 100, 100)}; --c: #0096c7;">
            <div class="gauge-inner">
              <div class="gauge-value">${data.waterQuality.ph}</div>
              <div class="gauge-unit">pH</div>
            </div>
          </div>
          <div class="gauge-label">pH Level</div>
        </div>
        <div class="gauge-chart">
          <div class="gauge-radial" style="--p: ${Math.min(data.waterQuality.dissolvedOxygen / 10 * 100, 100)}; --c: ${data.waterQuality.dissolvedOxygen < 4 ? '#dc2626' : '#16a34a'};">
            <div class="gauge-inner">
              <div class="gauge-value">${data.waterQuality.dissolvedOxygen}</div>
              <div class="gauge-unit">mg/L</div>
            </div>
          </div>
          <div class="gauge-label">Dissolved O₂</div>
        </div>
        <div class="gauge-chart">
          <div class="gauge-radial" style="--p: ${Math.min(data.waterQuality.bod / 20 * 100, 100)}; --c: ${data.waterQuality.bod > 10 ? '#dc2626' : '#d97706'};">
            <div class="gauge-inner">
              <div class="gauge-value">${data.waterQuality.bod}</div>
              <div class="gauge-unit">mg/L</div>
            </div>
          </div>
          <div class="gauge-label">BOD Level</div>
        </div>
        <div class="gauge-chart">
          <div class="gauge-radial" style="--p: ${Math.min(data.waterQuality.turbidity / 100 * 100, 100)}; --c: #d97706;">
            <div class="gauge-inner">
              <div class="gauge-value">${data.waterQuality.turbidity}</div>
              <div class="gauge-unit">NTU</div>
            </div>
          </div>
          <div class="gauge-label">Turbidity</div>
        </div>
      </div>
    </div>
    
    <!-- Authority Response -->
    <div class="detail-section">
      <h3>Authority Response</h3>
      <div class="detail-item">
        <div class="detail-value" style="background: rgba(0, 150, 199, 0.1); padding: 20px; border-radius: 12px; border-left: 4px solid #0096c7; line-height: 1.8;">
          <i class="fas fa-info-circle" style="color: #0096c7; margin-right: 10px;"></i>
          ${data.authorityResponse}
        </div>
      </div>
    </div>
  `;
}


// ===== 3. UTILITY FUNCTIONS =====

/**
 * Checks if the device is mobile
 * @returns {boolean} True if mobile device
 */
function isMobileDevice() {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

/**
 * Escapes HTML to prevent XSS
 * @param {string} text - Text to escape
 * @returns {string} Escaped text
 */
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

/**
 * Shows a notification message
 * @param {string} message - Message to display
 * @param {string} type - Type of notification (success, error, info)
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


// ===== 4. INITIALIZE SHARE PLATFORM BUTTON STYLES =====

// Add CSS for share platform buttons
const shareStyles = document.createElement('style');
shareStyles.textContent = `
  .share-platform-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    margin-bottom: 10px;
    background: var(--bg-secondary, #f9f9f9);
    border: 2px solid var(--border-color, rgba(0,0,0,0.1));
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    color: var(--text-primary, #1a1a1a);
  }
  
  .share-platform-btn:hover {
    transform: scale(1.03);
    box-shadow: 0 6px 20px rgba(0, 150, 199, 0.15);
    border-color: #00b4d8;
  }
  
  .share-platform-btn i {
    font-size: 1.4rem;
  }
  
  @keyframes slideInRight {
    from {
      transform: translateX(400px);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  
  @keyframes slideOutRight {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(400px);
      opacity: 0;
    }
  }
  
  [data-theme="dark"] #customShareMenu {
    background: #1a1a1a;
    border: 1px solid rgba(77, 208, 225, 0.2);
  }
  
  [data-theme="dark"] .share-platform-btn {
    background: rgba(26, 26, 26, 0.8);
    border-color: rgba(77, 208, 225, 0.3);
    color: #f5f5f5;
  }
`;
document.head.appendChild(shareStyles);


// ===== 5. LEGACY COMPATIBILITY =====

// Keep original openModal function for backward compatibility
function openModal(riverId) {
  openFullReportModal(riverId);
}

// Wrapper for toggleShareMenu to use new shareReport function
function toggleShareMenu(button, riverName) {
  // Call the new enhanced share function
  shareReport(riverName.charAt(0).toUpperCase() + riverName.slice(1));
}

console.log('✓ RiverVibe Reports Enhanced - Interactive features loaded successfully!');
