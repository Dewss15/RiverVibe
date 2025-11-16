// 🌊✨ RiverVibe - Dynamic JavaScript Functionality ✨🌊

// ===== Theme toggle now handled by theme.js =====

// ===== Scroll Animations =====
function initScrollAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-in');
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Observe all elements with animation classes
  document.querySelectorAll('.fade-in, .slide-up, .card').forEach(el => {
    observer.observe(el);
  });
}

// ===== Smooth Scrolling for Anchor Links =====
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (href !== '#') {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      }
    });
  });
}

// ===== Navbar Scroll Effect =====
function initNavbarScroll() {
  const nav = document.querySelector('nav');
  if (!nav) return;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
  });
}

// ===== Modal Functionality =====
function initModals() {
  // Close modal when clicking outside
  window.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
      closeAllModals();
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeAllModals();
    }
  });

  // Close button functionality
  document.querySelectorAll('.modal-close, .close-button').forEach(btn => {
    btn.addEventListener('click', closeAllModals);
  });
}

function closeAllModals() {
  document.querySelectorAll('.modal').forEach(modal => {
    modal.classList.remove('active');
  });
  document.body.style.overflow = 'auto';
}

function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

// ===== Share Functionality =====
function initShareButtons() {
  document.querySelectorAll('[data-share]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const shareData = {
        title: btn.getAttribute('data-share-title') || 'RiverVibe Report',
        text: btn.getAttribute('data-share-text') || 'Check out this pollution report on RiverVibe',
        url: window.location.href
      };
      
      if (navigator.share) {
        navigator.share(shareData).catch(err => console.log('Error sharing:', err));
      } else {
        copyToClipboard(shareData.url);
        showNotification('Link copied to clipboard!');
      }
    });
  });
}

function copyToClipboard(text) {
  const textarea = document.createElement('textarea');
  textarea.value = text;
  textarea.style.position = 'fixed';
  textarea.style.opacity = '0';
  document.body.appendChild(textarea);
  textarea.select();
  document.execCommand('copy');
  document.body.removeChild(textarea);
}

function showNotification(message, duration = 3000) {
  const notification = document.createElement('div');
  notification.className = 'notification';
  notification.textContent = message;
  document.body.appendChild(notification);
  
  setTimeout(() => notification.classList.add('show'), 100);
  setTimeout(() => {
    notification.classList.remove('show');
    setTimeout(() => notification.remove(), 300);
  }, duration);
}

// ===== Dynamic Footer Loading =====
function loadFooter() {
  const footer = document.querySelector('footer');
  if (footer) return; // Footer already exists
  
  const footerHTML = `
    <footer class="global-footer">
      <div class="footer-content">
        <div class="footer-section">
          <h3>RiverVibe</h3>
          <p>Protecting our rivers, one report at a time.</p>
        </div>
        <div class="footer-section">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="fullrep.html">Dashboard</a></li>
            <li><a href="feedback.html">Feedback</a></li>
            <li><a href="success.html">Success Stories</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Follow Us</h4>
          <div class="social-icons">
            <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn">
              <i class="fab fa-linkedin"></i>
            </a>
            <a href="https://instagram.com" target="_blank" aria-label="Instagram">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="https://github.com" target="_blank" aria-label="GitHub">
              <i class="fab fa-github"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>Made with 💙 by Team RiverVibe &copy; 2025</p>
      </div>
    </footer>
  `;
  
  document.body.insertAdjacentHTML('beforeend', footerHTML);
}

// ===== Page Load Animations =====
function initPageTransitions() {
  // Add fade-in effect to body
  document.body.style.opacity = '0';
  window.addEventListener('load', () => {
    setTimeout(() => {
      document.body.style.transition = 'opacity 0.5s ease';
      document.body.style.opacity = '1';
    }, 100);
  });

  // Handle navigation with fade effect
  document.querySelectorAll('a[href$=".html"]').forEach(link => {
    link.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href && !this.target) {
        e.preventDefault();
        document.body.style.opacity = '0';
        setTimeout(() => {
          window.location.href = href;
        }, 300);
      }
    });
  });
}

// ===== Card Hover Effects =====
function initCardEffects() {
  document.querySelectorAll('.card, .info-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-10px)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
    });
  });
}

// ===== Initialize All Features =====
document.addEventListener('DOMContentLoaded', () => {
  console.log('🌊 RiverVibe initialized');
  
  initScrollAnimations();
  initSmoothScroll();
  initNavbarScroll();
  initModals();
  initShareButtons();
  loadFooter();
  initPageTransitions();
  initCardEffects();
});

// ===== Utility Functions =====
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

// Export functions for use in other files
window.RiverVibe = {
  openModal,
  closeAllModals,
  showNotification,
  copyToClipboard
};
